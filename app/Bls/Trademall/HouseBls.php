<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/7/9
 * Time: 15:16
 */
namespace App\Bls\TradeMall;
use App\Bls\TradeMall\Model\House\HouseModel;
use Auth;

class HouseBls{

    public function getHouseList($searchForm = [],$order= ' id desc ',$pageSize = 20){
        $house = HouseModel::query()->where('shopId',Auth::user()->shop->id);
        if(isset($searchForm['type']) && !empty($searchForm['type'])){
            $house->where('type',$searchForm['type']);
        }
        if(isset($searchForm['startTime']) && !empty($searchForm['startTime'])){
            $house->where('created_at','>',$searchForm['startTime']);
        }
        if(isset($searchForm['endTime']) && !empty($searchForm['endTime'])){
            $house->where('created_at','<',$searchForm['endTime']);
        }
        return $house->orderByRaw($order)->paginate($pageSize);
    }
}