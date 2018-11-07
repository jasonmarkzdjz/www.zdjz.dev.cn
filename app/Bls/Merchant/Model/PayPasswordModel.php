<?php

namespace App\Bls\Merchant\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class PayPasswordModel extends BaseModel {

    protected $table= 'paypassword';  //指定表名

    protected $primaryKey= 'paypwdId';    //指定主键

    protected $fillable=['merchantId','payPwd','ip'];

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d', $value);
    }

}
