<?php

namespace App\Http\Controllers;

use App\Bls\Common\Model\ConfigPlugModel;
use App\Bls\Common\Model\NavModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use library\Client\HttpClient;
use library\Service\Common\CacheConst;
use library\Service\Contst\Common\StatusConst;
use library\Service\Response\JsonResponse;
use Auth;
use Qiniu\Config;

class Controller extends BaseController{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $pageSize = 20;
    protected $client;
//    public function __construct() {
//        view()->share('base_url', config("app.url"));
//        view()->share('assets_url', config("app.url"));
//        view()->share('navdata',$this->getNav());
//        view()->share('trademall',$this->getConfigMall());
//        $this->client = HttpClient::getInstance();
//    }
//    public function getToken(){
//        $result = $this->client->post('merchant/login',['form_params'=>['mobile'=>Auth::user()->mobile,'password'=>Auth::user()->cipher]]);
//        $result = json_decode($result->getBody(),true);
//        if($result['code'] == 200){
//            Cache::store('redis')->set(CacheConst::MERCHANT_API_TOKEN_LOGIN.Auth::user()->mobile,$result['data']['token'],3600);
//        }
//         return Cache::store('redis')->get(CacheConst::MERCHANT_API_TOKEN_LOGIN.Auth::user()->mobile);
//    }
    protected function retJson($data = []) {
        return JsonResponse::success($data);
    }

    protected function retError($status=0, $msg = '',$data = []) {

        return JsonResponse::error($status, $msg,$data);
    }

    protected function success()
    {
        return $this->retJson(200, '操作成功!');
    }

    public function getFenFormYuan($value)
    {
        return $value / 100;
    }
    public function getYuanFromFen($value)
    {
        return $value * 100;
    }
    public function getSumamount($amount,$amount2){
        return (int)($amount+$amount2);
    }

    public function getNav() {
        $nav = new NavModel();
       return $nav::query()->where('status',StatusConst::ENABLED)->orderByRaw('navsort desc')->get();
    }

    public function getConfigMall(){
        return ConfigPlugModel::query()->where('type',1)->where('isEnable',StatusConst::ENABLED)->get();
    }

}
