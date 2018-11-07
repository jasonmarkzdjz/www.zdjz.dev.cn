<?php

namespace App\Bls\Trademall;
use App\Bls\Trademall\Model\Hotels\HotelsModel;
use Auth;
/**
 * @authot Jaosn
 * @desc 酒店业务逻辑
 * @date 2018/05/20
 * */
class HotelsBls {

    /**
     * @authot jason
     * @desc 获取店铺信息
     * @data 2018/05/20
     * @return object
     * */
    public function getHotelsByList($searchData = [],$pageSize = 20,$orderBy = ' created_at desc') {
        $model = HotelsModel::query()->where('mId',Auth::user()->id);
        if(isset($searchData['start_time']) &&!empty($searchData['start_time'])) {
            $model->where('created_at','>',$searchData['end_time']);
        }
        if(isset($searchData['end_time']) && !empty($searchData['end_time'])){
            $model->where('created_at','<',$searchData['end_time']);
        }
        if(isset($searchData['shopName']) && !empty($searchData['shopName'])){
            $model->where('shopName','like',$searchData['shopName'].'%');
        }
        return $model->orderByRaw($orderBy)->paginate($pageSize);
    }
    /**
     * @author jason
     * @date 2018/05/20
     * @desc 设置店铺上下架
     * @return  boolean
     * */
    public function setHotels($hotelId = 0,$status) {
        $model = HotelsModel::find($hotelId);
        if(!$model->isEmpty()){
            $model->status = $status;
            $affect = $model->save();
            return $affect;
        }
            return false;
    }
    /**
     * @author jason
     * @date 2018/05/20
     * @desc 获取酒店信息
     * @param $filed:查询的数据库字段 $v:字段对应的值
     * @return object
     * */
    public function getHotel($filed = 'id',$v) {
        return $filed == 'id' ? HotelsModel::find($v):HotelsModel::query()->where($filed,$v)->get();
    }

}