<?php

namespace App\Bls\Merchant\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class BankcardRecodeModel extends BaseModel {

    protected $table= 'bankcardrecode';  //指定表名

    protected $primaryKey= 'id';    //指定主键

    protected $fillable=['merchantId','accountId','appId','trueName','cardNo','money','ip'];

    public function bankinfo($id,$accountId,$appId,$input){ //提现记录明细
        $data = array(
            'merchantId' => $id,
            'accountId' =>$accountId,
            'appId' => $appId,
            'trueName' => $input['realusername'],
            'bankName' => $input['bankusername'],
            'cardNo' => $input['bankusercard'],
            'money' => $input['moneynums'] * 100,
            'ip' => $_SERVER['SERVER_ADDR']
        );
        $result = BankcardRecodeModel::create($data);
        return true;
    }

}
