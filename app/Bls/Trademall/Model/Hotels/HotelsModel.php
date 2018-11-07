<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/22
 * Time: 13:11
 * @desc 酒店店铺模型
 */
namespace App\Bls\Trademall\Model\Hotels;

use App\Bls\Trademall\Model\BaseModel;
use HaoLi\LaravelAmount\Traits\AmountTrait;

class HotelsModel extends BaseModel {

    use AmountTrait;

    protected $amountFields = ['dailyRent','timeRent','premium','deposit'];

    protected  $table = 'hotels';

}