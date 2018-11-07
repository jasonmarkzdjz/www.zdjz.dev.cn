<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/5
 * Time: 17:58
 */
namespace App\Bls\Merchant\Model;


class AccountModel extends BaseModel{
    protected $table= 'account';  //指定表名

    protected $fillable =['merchantId','blance','amount','ip','status'];
}