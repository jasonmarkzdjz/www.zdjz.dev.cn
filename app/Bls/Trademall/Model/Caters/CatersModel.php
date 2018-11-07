<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/22
 * Time: 15:03
 */

/**
 * @author Jason
 * @desc 餐饮模型数据
 * @date 2018/05/20
 * */
namespace App\Bls\Trademall\Model\Caters;


use App\Bls\Trademall\Model\BaseModel;
use HaoLi\LaravelAmount\Traits\AmountTrait;

class CatersModel extends BaseModel {

    use AmountTrait;

    protected $table = 'caters';

    protected $amountFields = ['amount'];

}