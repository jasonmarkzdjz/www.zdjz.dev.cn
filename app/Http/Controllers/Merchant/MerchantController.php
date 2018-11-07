<?php

namespace App\Http\Controllers\Merchant;

use App\Bls\Merchant\MerchantBls;
use App\Bls\Merchant\Model\AccountModel;
use App\Bls\Merchant\Model\AreaModel;
use App\Bls\Merchant\Model\MerchantModel;
use App\Bls\Merchant\Model\PayConfigModel;
use App\Bls\Merchant\Model\UserprofileModel;
use App\Bls\Merchant\Model\PersonauthModel;
use App\Bls\Merchant\Model\CompanyauthModel;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use library\Service\Contst\Common\StatusConst;
use library\Service\Contst\Common\UserTypeConst;
use library\Service\Contst\PayConst;
use library\Service\File\TMFile;
use library\Service\Response\JsonResponse;
use Redis;


class MerchantController extends Controller {


    /**
     * @author jason
     * @desc 商户登录入口
     * @date 2018/05/20
     * */
    public function merchantLogin() {
        if(!Auth::check()){
            return View::make('merchant.login');
        }else{
            return redirect()->route('home.index');
        }
    }

    /**
     * @author jason
     * @desc 商户登陆
     *
     * */
    public function merchantStoreLogin(Request $request){
        $input = Input::all();
        $bls = new MerchantBls();
        $merchant = $bls->getMerchant($input['log_mobile'],'mobile')->first();
        if(!empty($merchant) && $merchant->count()){
            if(Hash::check(trim($input['log_password']),$merchant->password)){
                $merchant = MerchantModel::find($merchant->id);
                if($input['remember_token']){
                    Auth::login($merchant,true);
                }
                Auth::login($merchant);
                return Response::json(['code'=>1,'data'=>['message'=>'success']]);
            }else{
                return Response::json(['code'=>0,'data'=>['message'=>'密码不正确']]);
            }
        }else{
            return Response::json(['code'=>0,'data'=>['message'=>'该用户不存在']]);
        }
    }

    public function merchantRegister() {
        if(!Auth::check()){
            return View::make('merchant.register');
        }else{
            return View::make('merchant.info');
        }
    }

    /**
     *@desc 退出登陆
     *
     * */
    public function merchantLogout(){
        Auth::logout();
        return redirect()->route('home.index');

    }
    public function merchantRegistStore() {
        $input = Input::all();

        if(!\helper::isMobile($input['reg_mobile'])){
            return Response::json(['code'=>0,'data'=>['message'=>'手机号格式不正确！']]);
        }
        $bls = new MerchantBls();
        $merchant = $bls->getMerchant($input['reg_mobile'],'mobile')->first();
        if(!empty($merchant) && $merchant->count()){
            return Response::json(['code'=>0,'data'=>['message'=>'该账号已存在']]);
        }
        if(!\helper::checkSms($input['captcha'],$input['reg_mobile'])){
            return Response::json(['code'=>0,'data'=>['message'=>'短信验证码已过期或者错误']]);
        }
        if(!\helper::checkPassword($input['reg_password'])){
            return Response::json(['code'=>0,'data'=>['message'=>'请输入6~16位密码！']]);
        }

        $merchant = MerchantModel::create(['mobile'=>$input['reg_mobile'],'password'=>Hash::make($input['reg_password']),'cipher'=>$input['reg_password']]);
        if($merchant){
            AccountModel::create(['merchantId'=>$merchant->id,'blance'=>'0','amount'=>'0','status'=>StatusConst::ENABLED,'ip'=>\helper::getClientIp()]);
            return Response::json(['code'=>1,'data'=>$merchant]);
        }
    }


    /*
     * @desc 用户中心
     * @author:jason
     * @date 2018/05/20
     * */
    public function ucenter(){
        $userId = Auth::user()->id;
        $amount = AccountModel::where('merchantId',$userId)->first(['blance']);
        return View::make('merchant.ucenter.index',['blance'=>round($amount->blance / 100, 2)]);
    }

    
    /**
     * 编辑资料
     */
    public function editInformation(Request $request)
    {
        $userId = Auth::user()->id;
        $phone = Auth::user()->mobile;
        $userInfo = UserprofileModel::where('merchantId',$userId)->first();
        if ($request->isMethod('post')) {
            $input = Input::all();
            $user['name'] = $input['nickname'];
            $user['avatar'] = $input['avatar'];
            MerchantModel::where('id',$userId)->update($user);
            return UserprofileModel::addChange($userId,$input,$userInfo);            
        }
        return view('merchant.ucenter.information',['info'=>$userInfo]);
    }

    /**
     * 个人认证
     */
    public function personalAuth(Request $request) {
        $userInfo = PersonauthModel::where('merchantId',Auth::user()->id)->first();
        if ($request->isMethod('post')) {
            $input = Input::all();
            $validator = Validator::make($input,['upper'=>'required','under'=>'required',]);
            if($validator->fails()) return Response::json(['code'=>0,'message'=>'请上传身份证照片！']);
            return PersonauthModel::addChange(Auth::user()->id,$input,$userInfo);
        }
        return view('merchant.ucenter.personal',['userInfo'=>$userInfo]);
    }

    /**
     * 企业认证
     */
    public function enterpriseAuth(Request $request) {
        $companyInfo = CompanyauthModel::where('merchantId',Auth::user()->id)->first();

        if ($request->isMethod('post')) {
            $input = Input::all();
            $validator = Validator::make($input,['personUp'=>'required','personUn'=>'required','license'=>'required']);
            if($validator->fails()) return Response::json(['code'=>0,'message'=>'请上传认证资料照片！']);
            return CompanyauthModel::addChange(Auth::user()->id,$input,$companyInfo);
        }
        return view('merchant.ucenter.enterprise',['cInfo'=>$companyInfo]);
    }

    /**
     * 修改手机号
     */
    public function phoneChange(Request $request)
    {
        $userId = Auth::user()->id;
        $phone = $request->get('phone');
        $oldPhone = $request->get('oldphone');        
        if (!\helper::isMobile($phone) || !\helper::isMobile($oldPhone))
            return Response::json(['code'=>0,'message'=>'请输入正确手机号码！']);
        if ($request->get('checkCode') != session($phone))
            return Response::json(['code'=>0,'message'=>'验证码错误！']);
        if(UserprofileModel::where('merchantId',$userId)->update(['mobile'=>$phone]))
            return Response::json(['code'=>1,'message'=>'修改成功！']);
    }

    /**
     * 重置密码
     */
    public function changePwd(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = Input::all();

            $user = MerchantModel::find(Auth::user()->id);

            if (!\helper::isMobile($input['phone']))
                return Response::json(['code'=>0,'message'=>'请输入正确手机号码！']);

            if (!\helper::checkSms($input['checkCode'],$input['phone']))
                return Response::json(['code'=>0,'message'=>'验证码错误！']);

            if (!Hash::check($input['pwd'],$user['password']))
                return Response::json(['code'=>0,'message'=>'原密码错误！']);
            MerchantModel::where('id',$user->id)->update(['password'=>Hash::make($input['newPwd']),'cipher'=>$input['newPwd']]);
            return Response::json(['code'=>1,'message'=>route('merchant.logout')]);
        }
        return view('merchant.ucenter.change');
    }

    /**
     * 上传认证图
     */
    public function upAuth(Request $request)
    {
        $file       = Input::file('file');
        $filePath   = $file->path();
        $fileSize   = $file->getClientSize();
        $extension  = $file->getClientOriginalExtension();
        if (!$request->get('authType'))
            return Response::json(['code'=>0,'message'=>'非法访问!']);
        if (!in_array($extension, ['jpg', 'png']))
            return Response::json(['code'=>0,'message'=>'请上传jpg或png格式文件！']);
        if ($fileSize > (2 * 1024 * 1024))
            return Response::json(['code'=>0,'message'=>'请上传2M以下图片！']);
        $fileName   = $request->get('lower').'.'.$extension;
        $newName    = 'Auth_'.$request->get('authType').'_'.Auth::user()->mobile.'_'.$fileName;
        $disk = \Storage::disk('qiniu');
        $disk->exists($newName) && $disk->delete($newName);
        if ($disk->put($newName,file_get_contents($filePath))){
            return Response::json([
                'code'=>1,
                'message'=>[$disk->url(['path' => $newName, 'domainType' => 'custom'])],
            ]);
        }else{
            return Response::json(['code'=>0,'message'=>'文件上传失败！']);
        }
    }

    public function payConfig(Request $request){
        if ($request->isMethod('post')) {
            $privitefile = $request->file('privatekey');
            $publicfile = $request->file('publicKey');
            $fileTypeArray = ['pem'];
            $payType = $request->get('payType');
            $xtbKey = $request->get('xtbKey');
            if(empty($payType)){
                return JsonResponse::error(0,'请选择支付类型');
            }
            if(empty($privitefile) || empty($publicfile)){
                return JsonResponse::error(0,'请上传文件');
            }
            if(in_array($privitefile->getClientMimeType(),$fileTypeArray) || in_array($publicfile->getClientMimeType(),$fileTypeArray)){
                return JsonResponse::error(0,'非法的文件类型禁止上传');
            }
            $privateOriginName = $privitefile->getClientOriginalName();
            $originNameArray = explode('.',$privateOriginName);
            if(empty($originNameArray) || $originNameArray[0] != 'merchant_private_key'){
                return JsonResponse::error(0,'学通宝商户私钥名称错误');
            }
            $publicOriginName = $publicfile->getClientOriginalName();
            $originNameArray = explode('.',$publicOriginName);
            if(empty($originNameArray) || $originNameArray[0] != 'merchant_public_key'){
                return JsonResponse::error(0,'学通宝商户公钥名称错误');
            }
            if(empty($xtbKey)){
                return JsonResponse::error(0,'学通宝公钥内容不能为空');
            }
            $priviteextendName=$privitefile->getClientOriginalExtension();//商户私钥文件扩展名
            $priviteRealPath = $privitefile->getRealPath();   //临时文件的绝对路径
            $publicfileExtensionName = $publicfile->getClientOriginalExtension();//商户公钥文件扩展名
            $publicRealPath = $publicfile->getRealPath();   //临时文件的绝对路径
            $priviteNewFilename = Auth::user()->mobile.'_merchant_private_key'.'.'.$priviteextendName;
            $publiteNewFilename = Auth::user()->mobile.'_merchant_public_key'.'.'.$publicfileExtensionName;
            $xtbKey = Auth::user()->mobile.'_xtb_key.pem';
            //判断该商户是否已经配置
            $payconfig = Auth::user()->payConfig;
            $isExists = false;
            if(!empty($payconfig)){
                $isExists = true;
                $priviteNewFilename = $payconfig->privateKey;
                $publiteNewFilename = $payconfig->publicKey;
                $xtbKey = $payconfig->xtbKey;
            }
            if(Storage::exists($priviteNewFilename)  || Storage::exists($publiteNewFilename) || Storage::exists($xtbKey)){ //如果文件已存在 删除源文件  重新进行上传
                Storage::delete($priviteNewFilename);
                Storage::delete($publiteNewFilename);
                Storage::delete($xtbKey);
            }
            $file = TMFile::getInstance();
            $file->execute(storage_path().'/cert/',$xtbKey,$request->get('xtbKey'));
            $isPrivate= Storage::disk('cert')->put($priviteNewFilename, file_get_contents($priviteRealPath));
            $isPublic = Storage::disk('cert')->put($publiteNewFilename, file_get_contents($publicRealPath));
            $payment = [];
            foreach ($payType as $key){
                $payment[$key]['key'] = $key;
                $payment[$key]['item'] = PayConst::getPayTypeItem($key);
            }
            $config = new PayConfigModel();
            if($isExists){
                $config = PayConfigModel::find($payconfig->id);
            }
            if($isPrivate && $isPublic){
                $config->mId=Auth::user()->id;
                $config->payType=json_encode($payment);
                $config->publicKey=$publiteNewFilename;
                $config->privateKey=$priviteNewFilename;
                $config->xtbKey=$xtbKey;
                $config->ip = \helper::getClientIp();
                $config->save();
                return JsonResponse::success();
            }
        }
        $payconfig = Auth::user()->payConfig;
        $config = [];
        if(!empty($payconfig)){
            $config = PayConfigModel::find($payconfig->id);
            $config->paytype = json_decode($config->payType,true);
            $config->xtbKey = file_exists(storage_path().'/cert/'.$config->xtbKey) ? file_get_contents(storage_path().'/cert/'.$config->xtbKey) :'';
        }
        return View::make('account.payConfig',['payconfig'=>$config]);
    }
}