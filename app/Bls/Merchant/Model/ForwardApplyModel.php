<?php

namespace App\Bls\Merchant\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class ForwardApplyModel extends BaseModel {

    protected $table= 'forwardapply';  //指定表名

    protected $primaryKey= 'id';    //指定主键

    protected $fillable=['merchantId','accountId','forwardType','serialNo','amount','commission','params','status','outTradeNo','ip'];

    public function withdrawinfo($id,$accountId,$order,$input){ //提现记录明细
        $data = array(
            'merchantId' => $id,
            'accountId' =>$accountId,
            'forwardType' => $input['status'],
            'serialNo' => $order,
            'amount' => $input['moneynums'] * 100,
            'status' => 0,
            'ip' => $_SERVER['SERVER_ADDR']
        );
        $result = ForwardApplyModel::create($data);
        return $result->id;
    }

}
