<?php

namespace App\Http\Controllers\Account;

use App\Bls\Merchant\Model\AccountBankcardrecodeModel;
use App\Bls\Merchant\Model\AccountFinanceModel;
use App\Bls\Merchant\Model\AccountModel;
use App\Bls\Merchant\Model\AccountPaypwdModel;
use App\Bls\Merchant\Model\AlipayRecodeModel;
use App\Bls\Merchant\Model\BankcardRecodeModel;
use App\Bls\Merchant\Model\ForwardApplyModel as withdrawaldetails;
use App\Bls\Merchant\Model\ForwardApplyModel;
use App\Bls\Merchant\Model\MerchantModel;
use App\Bls\Merchant\Model\PayPasswordModel;
use App\Bls\Merchant\Model\PersonauthModel;
use App\Bls\Merchant\Model\RechargeModel;
use App\Http\Controllers\Controller;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use library\Client\HttpClient;
use library\Service\ApiSendMessage\SendMessage;
use library\Service\Common\CacheConst;
use library\Service\Contst\Common\CertStatusConst;
use library\Service\Contst\Common\StatusConst;
use App\Http\Controllers\Common\SmsController;
use Illuminate\Redis;

class AccountController extends Controller {

    public function __construct(){
        parent::__construct();
        $this->middleware('merchant.groupauth');
    }

    /**
     * @author :
     * @desc
     * @date
     * @param
     * */
    public function perAccount(Request $request){
        $id =  Auth::user()->id;
        $input = Input::all();
        $input['types'] = isset($input['types'])|| !empty($input['types']) ? intval($input['types']) : StatusConst::CODE_ALIPAY;
        $input['starttime'] = isset($input['starttime']) ? intval($input['starttime']) : '';
        $input['endtime'] = isset($input['endtime']) ? intval($input['endtime']) :'';
        $model = RechargeModel::query()->where('merchantId',$id);
        if(!empty($input['types'])){
            $model->where('rechargeType',$input['types']);
        }
        if(!empty($input['starttime'])){
            $model->where('created_at','>',$input['starttime']);
        }
        if(!empty($input['endtime'])){
            $model->where('created_at','<',$input['endtime']);
        }
        $results = $model->paginate($this->pageSize);
        !empty($input['types']) ? $results->appends(['types'=>$input['types']]) :'';
        !empty($input['starttime']) ? $results->appends(['starttime'=>$input['starttime']]) :'';
        !empty($input['endtime']) ? $results->appends(['types'=>$input['endtime']]) :'';
        $data = array(
            'weixinpay' => StatusConst::CODE_WEIXINPAY,
            'alipay' => StatusConst::CODE_ALIPAY
        );
        return View::make('account.peraccount',['data' => $data,'result' => $results,'searchdata'=>$input]);
    }

    public function myWallet(){
        $id =  Auth::user()->id;
        $result = PayPasswordModel::where('merchantId','=',$id) ->first();
        $data['paypwd'] = !empty($result->payPwd)?str_replace($result->payPwd,'******',$result->payPwd):''; //支付密码
        $data['mobile'] = Auth::user()->mobile;
        $data['payid']  = !empty($result->payid)?$result->payid:'';
        return View::make('account.mywallet',compact('data',$result));
    }

    //设只支付密码的短信验证码
    public function payPwd(Request $request){
        $input = Input::all();
        $phone = $input['phoned'];
        $codeways = $input['codeways']; //支付密码获取验证码状态 或者 提现方式为支付宝
        $bankcodes = $input['bankcode']; //绑定银行卡获取验证码状态  或者提现方式为网银
        $client = HttpClient::getInstance();
        $sms = $client->get('sms/smssend',['query'=>['mobile'=>$phone,'action'=>2]]);
        $sms = json_decode($sms->getBody(),true);
        if(!empty($sms['data']) && $sms['data']['Code'] == 'OK') {
            Cache::store('redis')->set(CacheConst::SEND_MESSAGE_VR_LOGIN . $phone, serialize($sms['data']['smsCode']), 60);
          if($codeways == StatusConst::CODE_STATUS && $bankcodes == ''){
                return array('num' => $codeways,'msg' =>'短信发送成功','status' => StatusConst::CODE_STATUS );
            }else if($bankcodes == StatusConst::CODE_SATUS && $codeways == ''){
                return array('num' => $bankcodes,'msg' =>'短信发送成功','status' => StatusConst::CODE_STATUS);
            }
        }
    }

    //支付密码设置
    public function paySet(Request $request){
        $input = Input::all();
        $pwd = Hash::make($input['paypwdone']);  //用户设置的支付密码
        $paycodes = $input['paycodes']; //短信验证码
        if (!\helper::checkSms($paycodes,$input['phoned'])){
            return Response::json(['status' => StatusConst::CODE_STATUS]);
        } else{
            $id = Auth::user()->id;  //用户ID
            $data = array(
                'merchantId' => $id,
                'payPwd' => $pwd,
                'ip' => $_SERVER['SERVER_ADDR']
            );
            $result = PayPasswordModel::where('merchantId','=',$id) -> get(); //判断是否存在该条数据
            if($result -> isEmpty()){
                $res = PayPasswordModel::create($data);   //不存在添加
                if($res){
                    return Response::json(['status' => true]);
                }else{
                    return Response::json(['status' => false]);
                }
            }else{
                $res = PayPasswordModel::where('merchantId','=',$id) -> update($data);  //存在更新
                if($res){
                    return Response::json(['status' => true]);
                }else{
                    return Response::json(['status' => false]);
                }
            }
        }

    }
    //提现
    public function withDraw(Request $request){
       $input = Input::all();
       $status = $input['status'];                       //状态为1是支付宝提现，状态为2是网银提现
       $codes = $input['code'];                          //验证码
       $moneynums = $input['moneynums'] * 100;          //提现金额,以分计单位
       $code = Cache::store('redis') ->get(CacheConst::SEND_MESSAGE_VR_LOGIN . $input['phoned']);
       $order = \helper::getOrderno();
       $merchant =MerchantModel::find(Auth::user()->id);
        if($code == '' || $codes != $code){
            return Response::json(['status' => StatusConst::CODE_STATUS]);  //验证码失效
        }
        if(!$merchant->status || empty($merchant->auth) || $merchant->auth->isExamin != CertStatusConst::AUTH_I){
            return Response::json(['status' => StatusConst::CODE_USERAUTH]); //该用户未认证所以不能提现
        }
       if($moneynums > $merchant->account->amount){
            return Response::json(['status' => StatusConst::CODE_USERMONEY]);   //如果提现金额大于原有可提现的金额则不能提现
        }
        $appId = (new withdrawaldetails()) -> withdrawinfo(Auth::user()->id,$merchant->account->id,$order,$input);
        if($status == StatusConst::CODE_USERPAY){                //支付宝提现方式
            $res = (new AlipayRecodeModel()) -> alipay(Auth::user()->id,$merchant->account->id,$appId,$input);
        }else if($status == StatusConst::CODE_USERPAYBANK){     //网银提现方式
            $res = (new BankcardRecodeModel()) -> bankinfo(Auth::user()->id,$merchant->account->id,$appId,$input);
        }
        if($res){
            return Response::json(['status' => true]);
        }else{
            return Response::json(['status' => false]);
        }
    }
}
