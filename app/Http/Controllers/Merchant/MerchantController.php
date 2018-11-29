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
     echo "欢迎您。".rand(1000,9999);exit; 
     }
}
