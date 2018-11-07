<?php

namespace App\Bls\Merchant\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class AlipayRecodeModel extends BaseModel {

    protected $table= 'alipayrecode';  //指定表名

    protected $primaryKey= 'Id';    //指定主键

    protected $fillable=['merchantId','accountId','appId','alipay','alipaymoney','ip'];

    public function alipay($id,$accountId,$appId,$input){
        $data = array(
            'merchantId' => $id,
            'accountId' =>$accountId,
            'appId' => $appId,
            'alipay' => $input['payaccounts'],
            'alipaymoney' => $input['moneynums'] * 100,
            'ip' => $_SERVER['SERVER_ADDR']
        );
        $result = AlipayRecodeModel::create($data);
        return true;
    }

}
