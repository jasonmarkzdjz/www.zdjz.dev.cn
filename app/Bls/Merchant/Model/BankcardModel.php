<?php

namespace App\Bls\Merchant\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class BankcardModel extends BaseModel {

    protected $table= 'bankcard';  //指定表名

    protected $primaryKey= 'id';    //指定主键

    protected $fillable=['merchantId','accountId','bankName','cardNo','holder','alipayAccount','mobile','ip'];

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d', $value);
    }

}
