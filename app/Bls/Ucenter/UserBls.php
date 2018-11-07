<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/19
 * Time: 21:50
 */
namespace App\Bls\Ucenter;
use App\Bls\Ucenter\Model\Account\RechangeModel;
use Auth;
class UserBls {
    /**
     * 用户充值记录
     *
     * */
    public function getRechangeList($searchData =[],$order = ' id desc',$pageSize = 20){
        $rechange = RechangeModel::query()->where('uid',1);

        if(!isset($searchData['rechargeType']) && !empty($searchData['rechargeType'])){
            $rechange->where('rechargeType',$searchData['rechargeType']);
        }
        if(!isset($searchData['rechargeSerialNo']) && !empty($searchData['rechargeSerialNo'])){
            $rechange->where('rechargeSerialNo',$searchData['rechargeSerialNo']);
        }
        if(!isset($searchData['status']) && !empty($searchData['status'])){
            $rechange->where('status',$searchData['status']);
        }

        return $rechange->orderByRaw($order)->paginate($pageSize);
    }
}