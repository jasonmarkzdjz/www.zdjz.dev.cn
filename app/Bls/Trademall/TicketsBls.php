<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/24
 * Time: 10:59
 */
namespace App\Bls\Trademall;
use App\Bls\Trademall\Model\Tickets\TicketsModel;
use Auth;
class TicketsBls {

    /**
     * @authpr:jason
     * @desc 获取票务信息
     * @date 2018/05/24
     * @return object
     * */
    public function getTicketsList($searchData = [],$pageSize = 20,$orderBy = ' id desc') {
        $model = TicketsModel::query()->where('mId',Auth::user()->id);
        if (isset($searchData['startTime']) && !empty($searchData['startTime'])) {
            $model->where('created_at', '>', $searchData['startTime']);
        }
        if (isset($searchData['endTime']) && !empty($searchData['end_time'])) {
            $model->where('created_at', '<', $searchData['endTime']);
        }
        if (isset($searchData['ticketsName']) && !empty($searchData['ticketsName'])) {
            $model->where('ticketsName', 'like', $searchData['ticketsName'] . '%');
        }
        return $model->orderBy($orderBy)->paginate($pageSize);
    }

    /**
     * @author jason
     * @desc 设置商品上下架
     * @return object
     * */
    public function setTicketsStatus($hotelId = 0,$status) {
        $model = TicketsModel::find($hotelId);
        if(!$model->isEmpty()){
            $model->status = $status;
            $affect = $model->save();
            return $affect;
        }
            return false;
    }

    /**
     * @author jason
     * @desc 获取票务详细信息
     * @date 2018/5/20
     * @params filed :表示要查询的字段 $v：字段对应的值
     * @return object
     * */
    public function getTickets($v,$filed = 'id'){
        return $filed == 'id' ? TicketsModel::find($v):TicketsModel::query()->where($filed,$v)->get();
    }
}