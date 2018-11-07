<?php

namespace App\Bls\Merchant\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class RechargeModel extends BaseModel {

    protected $table= 'recharge';  //指定表名


    protected $fillable=['merchantId','rechargeType','rechargeSerialNo','amount','outTradeNo','status','ip'];




    public function pay($input,$id,$order_sn){
        $data = array(
            'merchantId' => $id,
            'rechargeType' => $input['recharge'],
            'rechargeSerialNo' => $order_sn,
            'amount' => $input['paymoneys'] * 100,
            'status' => 1,
            'ip' => $_SERVER['SERVER_ADDR']
        );
        $result = RechargeModel::create($data);
        return $result;
    }



}
