<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/27
 * Time: 18:39
 */
namespace App\Http\Controllers\Common;
use App\Bls\Merchant\Model\RechargeModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use library\Client\HttpClient;
use library\Service\Contst\Common\RechangeConst;
use library\Service\Contst\PayConst;
use library\Service\Response\JsonResponse;
use Psy\Util\Json;
use Validator,Auth;

class LeanTongBaoPayController extends Controller{

    public function rechange(){
        $input = Input::all();
        $validator = Validator::make(
            $input,
            ['amount' => 'required|Numeric','rechargeType' => 'required|'.Rule::in(RechangeConst::desc())],
            ['amount.required' => '充值金额不能为空', 'amount.Numeric' => '充值金额为整数', 'rechargeType.required'=>'请选择充值方式', 'rechargeType.in' => '请选择充值方式']
        );
        if ($validator->fails()) {
            return JsonResponse::error(0, '验证失败', $validator->errors()->toArray());
        }
        $input['amount'] = '0.01';
        if(!\helper::getRechangeType($input['rechargeType'])){
            return JsonResponse::error(0,'请选择正确充值类型');
        }
        $result = $this->client->post('pay/unified',['form_params'=>['amount'=>$input['amount'],'rechargeType'=>$input['rechargeType'],'token'=>$this->getToken()]]);
        $result = json_decode($result->getBody(),true);
        if($result['code'] == 200){
            return JsonResponse::success($result['data']);
        }
        return JsonResponse::error(0,$result['message']);
    }
    //订单监测
    public function checkOrder(Request $request){
        $merchantOrderNo = $request->get('merchantOrderNo');
        $result = $this->client->post('pay/receivefront',['form_params'=>['merchantOrderNo'=>$merchantOrderNo,'token'=>$this->getToken()]]);
        $result = json_decode($result->getBody(),true);
        return $result;
    }
}