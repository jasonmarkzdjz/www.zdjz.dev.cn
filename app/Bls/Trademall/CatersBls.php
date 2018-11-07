<?php

/**
 * @author jaosn
 * @desc 餐饮业务
 * @date 2018/05/20
 * */
namespace App\Bls\Trademall;
use App\Bls\Trademall\Model\Caters\CatersModel;
use Auth;
class CatersBls {


    /**
     * @authpr:jason
     * @desc 获取票务信息
     * @date 2018/05/24
     * @return object
     * */
    public function getCatersList($searchData = [],$pageSize = 20,$orderBy = ' created_at desc') {
        $model = CatersModel::query()->where('mId',Auth::user()->id)->where('status',$searchData['status']);
        if (isset($searchData['startTime']) && !empty($searchData['startTime'])) {
            $model->where('created_at', '>', $searchData['startTime']);
        }
        if (isset($searchData['endTime']) && !empty($searchData['end_time'])) {
            $model->where('created_at', '<', $searchData['endTime']);
        }
        return $model->orderByRaw($orderBy)->paginate($pageSize);
    }


    /**
     * @author jason
     * @desc 详细信息
     * @return object
     * @param $filed:查询数据库字段 $v:字段对应的值
     * */
    public function getCaters($v,$filed = 'id') {
        return $filed == 'id' ? CatersModel::find($v): CatersModel::query()->where($filed,$v)->get();
    }

    /**
     * @author jason
     * @desc 设置商品上下架
     * @return object
     * */
    public function setInventory($cId = 0,$status) {
        $model = CatersModel::find($cId);
        if(!empty($model)){
            $model->status = $status;
            $affect = $model->save();
            return $affect;
        }
        return false;
    }
}