<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/21
 * Time: 10:15
 */
namespace App\Bls\Ucenter;

use App\Bls\Ucenter\Model\Account\RefundApplyModel;
use App\Bls\Ucenter\Model\Account\RefundLogModel;
use App\Bls\Ucenter\Model\Order\OrderModel;
use Illuminate\Support\Facades\DB;
use library\Service\Contst\Common\OrderTypeConst;
use library\Service\Contst\Common\UserTypeConst;

class OrderBls {

    //用户订单列表
    public function  getHotelsOrderList($searchData = [],$pageSize = 20,$orderBy = ' id desc') {
        $model = OrderModel::query()->where('mid',Auth::user()->id);
        if(isset($searchData['start_time']) &&!empty($searchData['start_time'])) {
            $model->where('created_at','>',$searchData['end_time']);
        }
        if(isset($searchData['end_time']) && !empty($searchData['end_time'])){
            $model->where('created_at','<',$searchData['end_time']);
        }
        if(isset($searchData['orderNo']) && !empty($searchData['orderNo'])){
            $model->where('orderNo','like',$searchData['orderNo'].'%');
        }
        if(isset($searchData['orderStatus']) && !empty($searchData['orderStatus'])){
            $model->where('orderStatus',$searchData['orderStatus']);
        }
        return $model->orderBy($orderBy)->paginate($pageSize);
    }

    /**
     * 发起订单退款申请
     *
     * */
    public function refundApply(OrderModel $order){
       try{
           DB::beginTransaction();
           $refund = new RefundApplyModel();
           $refund->uid = Auth::user()->id;
           $refund->orderId = $order->id;
           $refund->refundOrder = \helper::getOrderno();
           $refund->refundTime = date('Y-m-d H:i:s');
           $refund->refundAmount = $order->amount;
           $refund->cause = Input::get('cause');
           $affectId = $refund->save();
           //写日志
           $refundlog = new RefundLogModel();
           $refundlog->orderId = $order->id;
           $refundlog->refundapplyId = $affectId;
           $refundlog->uid = Auth::user()->id;
           $refundlog->utype = UserTypeConst::USCENTER;
           $refundlog->status = OrderTypeConst::CONFIRM_A_CAN;
           $refundlog->save();
           DB::commit();
           return true;
       }catch (\Exception $e){
           DB::rollBack();
            return false;
       }
        return false;
    }
}