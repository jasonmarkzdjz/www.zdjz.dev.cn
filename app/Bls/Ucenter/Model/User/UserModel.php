<?php

namespace App\Bls\Ucenter\Model\User;

use App\Bls\Ucenter\Model\Account\AccountModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserModel extends BaseModel implements AuthenticatableContract,
    AuthorizableContract, CanResetPasswordContract{

    use Authenticatable,Authorizable,CanResetPassword;

    protected $table = 'ucenter';

    //用户的收藏一对多
    public function collection(){
        return $this->hasMany(FavoritesModel::class,'uid');
    }

    //账户
    public function account(){
        return $this->hasOne(AccountModel::class,'uid');
    }
}