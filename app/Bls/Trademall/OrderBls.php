<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/21
 * Time: 9:32
 */

namespace App\Bls\Trademall;

use App\Bls\Trademall\Model\Order\OrderModel;

class OrderBls{

    public function  getHotelsOrderList($searchData = [],$pageSize = 20,$orderBy = ' id desc') {
        $model = OrderModel::query()->where('mid',Auth::user()->id);
        if(isset($searchData['start_time']) &&!empty($searchData['start_time'])) {
            $model->where('created_at','>',$searchData['end_time']);
        }
        if(isset($searchData['end_time']) && !empty($searchData['end_time'])){
            $model->where('created_at','<',$searchData['end_time']);
        }
        if(isset($searchData['orderType']) && !empty($searchData['orderType'])){

        }
        if(isset($searchData['orderNo']) && !empty($searchData['orderNo'])){
            $model->where('orderNo',$searchData['orderNo']);
        }

        if(isset($searchData['orderStatus']) && !empty($searchData['orderStatus'])){
            $model->where('orderStatus',$searchData['orderStatus']);
        }

        if(isset($searchData['checkStatus']) && !empty($searchData['checkStatus'])){
            $model->where('checkStatus',$searchData['checkStatus']);
        }

        return $model->orderBy($orderBy)->paginate($pageSize);
    }
}