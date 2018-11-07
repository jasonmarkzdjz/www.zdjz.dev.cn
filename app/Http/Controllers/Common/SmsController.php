<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/19
 * Time: 20:38
 */
namespace App\Http\Controllers\Common;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use library\Client\HttpClient;
use library\Service\Common\CacheConst;
use library\Service\Contst\Api\SendMessageApiConst;

class SmsController extends Controller{


    public function getSms(){
        $mobile = Input::get('mobile');
        $action = Input::get('action');
        $isMobile = \helper::isMobile($mobile);
        if(!$isMobile){
            return $this->retError(0,'请输入正确的手机号');
        }
        if(!$action){
            return $this->retError(0,'执行动作不能为空');
        }
        try{
            $client = HttpClient::getInstance();
            $sms = $client->get('sms/smssend',['query'=>['mobile'=>$mobile,'action'=>$action]]);
            $sms = json_decode($sms->getBody(),true);
            if(!empty($sms['data']) && $sms['data']['Code'] == 'OK'){
                Cache::store('redis')->set(CacheConst::SEND_MESSAGE_VR_LOGIN.$mobile,serialize($sms['data']['smsCode']),60);
                return $this->success();
            }
            return $this->retError(0,'短信验证码获取失败');
        }catch (\Exception $exception){
            return $this->retError(0,'短信验证码获取失败');
        }
    }
}
