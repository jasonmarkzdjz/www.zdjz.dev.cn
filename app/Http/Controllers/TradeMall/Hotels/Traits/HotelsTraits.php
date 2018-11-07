<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/24
 * Time: 13:00
 */
namespace App\Http\Controllers\TradeMall\Hotels;
use Illuminate\Support\Collection;
use library\Service\Contst\Common\StatusConst;
use library\Service\Contst\Hotels\HotelsConsts;

trait HotelsTraits{

    public function formHotelsFormater(Collection $items){
        return $items->each(function ($item) {
            //对集合进行处理
            $item->checkInTypeDesc = HotelsConsts::getcheckInTypeItem($item->checkInType);

            $item->toiletDesc = HotelsConsts::getToiletItem($item->toilet);

            $item->BedDesc = HotelsConsts::getBedItem($item->bedInstead);
        });
    }
}