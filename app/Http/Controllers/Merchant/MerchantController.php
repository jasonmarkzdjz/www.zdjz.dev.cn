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
use app\Http\Controllers\Factory\适配器模式\AdaptorController;
use App\Http\Controllers\工厂方法\HuaweiFactoryController;
use App\Http\Controllers\工厂方法\XiaoMiFactoryController;
use App\Http\Controllers\工厂模式\FactoryController;
use App\Http\Controllers\策略模式\GirlFrendController;
use App\Http\Controllers\策略模式\KeAiController;
use app\Http\Controllers\适配器模式\WifeController;
use App\Http\Controllers\门面模式\FacdeController;
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
//     echo "欢迎您。".rand(1000,9999);exit;
//
//      //静态工厂模式
//        $person = FactoryController::createFactory('person');
//        $jinling = FactoryController::createFactory('jinling');
//        //工厂方法
//        $xiaomi = XiaoMiFactoryController::createPhpne();
//        $huawei = HuaweiFactoryController::createPhpne();
//
//        //门面模式
//        $facde = new FacdeController();
//        $facde->start();
//        //适配器模式
//        $wife = new WifeController();
//        $adaptor = new AdaptorController($wife);
//        $adaptor->cook();
//        $adaptor->writePhp();
//        //策略模式
//        $keai = new KeAiController();
//        $girlfrend = new GirlFrendController($keai);
//        $girlfrend->sajiao();




     }
}
