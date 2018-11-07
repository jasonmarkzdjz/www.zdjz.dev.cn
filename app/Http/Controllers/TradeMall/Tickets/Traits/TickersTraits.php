<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/24
 * Time: 13:00
 */
namespace App\Http\Controllers\TradeMall\Tickets;
use Illuminate\Support\Collection;
use library\Service\Contst\Common\StatusConst;

trait TickersTraits{


    public function formatTicketBatch(Collection $items){
        return $items->each(function ($item) {
            //对对象进行处理
            $item->distributionDesc = StatusConst::getDescByItem($item->isDistribution);
            $catersPriceSlot = !is_null(json_decode($item->catersPrice,true)) ? json_decode($item->catersPrice,true):[];
            $item->adultPrice = !empty($catersPriceSlot['adultPrice']) ? $catersPriceSlot['adultPrice'] : '';
            $item->childrenPrice = !empty($catersPriceSlot['childrenPrice']) ? $catersPriceSlot['childrenPrice'] : '';
            $item->studentPrice = !empty($catersPriceSlot['studentPrice']) ? $catersPriceSlot['studentPrice'] : '';
            $catersTimeSlot= !is_null(json_decode($item->catersTime,true)) ? json_decode($item->catersTime,true) : [];
            $item->catersAm = !empty($catersTimeSlot['catersAm']) ? $catersTimeSlot['catersAm'] : '';
            $item->catersPm = !empty($catersTimeSlot['catersPm']) ? $catersTimeSlot['catersPm'] : '';
        });
    }
}