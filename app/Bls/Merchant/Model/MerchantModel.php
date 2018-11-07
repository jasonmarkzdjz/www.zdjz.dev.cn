<?php

namespace App\Bls\Merchant\Model;



use App\Bls\TradeMall\Model\ShopModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use library\Service\Contst\Common\UserTypeConst;


/**
 * 用户资料模型
 * @authon jason
 *
 * */
class MerchantModel extends BaseModel implements AuthenticatableContract,
    AuthorizableContract, CanResetPasswordContract{

    use Authenticatable,Authorizable,CanResetPassword;

    protected $table = 'merchant';

    protected $fillable =['mobile','name','password','cipher','remember_token','customertype'];

    public function account(){
        return $this->hasOne(AccountModel::class,'merchantId');
    }

    public function merchantProfile() {
       return $this->hasOne(MerchantProfileModel::class, 'merchantId','id');
    }
    public function auth(){
        if($this->customertype == UserTypeConst::PERSONTYPE){
            return $this->hasOne(PersonauthModel::class, 'merchantId','id');
        }else if($this->customertype == UserTypeConst::COMPANYTYPE){
            return $this->hasOne(CompanyauthModel::class, 'merchantId','id');
        }
    }
    public function payConfig(){
        return $this->hasOne(PayConfigModel::class,'mId');
    }

    public function shop(){
        return $this->hasOne(ShopModel::class,'mId');
    }
}