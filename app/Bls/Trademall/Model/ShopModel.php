<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/7/4
 * Time: 14:34
 */
namespace App\Bls\TradeMall\Model;

class ShopModel extends BaseModel{

    protected $table='shop';

    public function shopBanner(){
        return $this->hasMany(ShopBannerModel::class,'shopId');
    }
}