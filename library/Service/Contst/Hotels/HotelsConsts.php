<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/28
 * Time: 15:25
 */
namespace library\Service\Contst\Hotels;

class HotelsConsts{

    const SINGLE_ROOM = 1;
    const BED_ROOM = 2;
    const DOUBLE_ROOM = 3;
    const FAMILY_ROOM = 4;
    const BUSINESS_ROOM = 5;

    const EVERY_GUEST = 1;
    const EVERY_DAY = 2;

    const PRIVATE_TOILET = 1;
    const PUBILC_TOILET = 2;

    const SINGLE_ROOM_DESC = '单人房';
    const BED_ROOM_DESC = '大床房';
    const DOUBLE_ROOM_DESC = '双床房';
    const FAMILY_ROOM_DESC = '家庭房';
    const BUSINESS_ROOM_DESC = '商务房';


    const EVERY_GUEST_DESC = '每客';
    const EVERY_DAY_DESC = '每日';

    const PRIVATE_TOILET_DESC = '独立卫生间';
    const PUBILC_TOILET_DESC = '公共卫生间';

    public static function getcheckInType()
    {
        return [
            self::SINGLE_ROOM => self::SINGLE_ROOM_DESC,
            self::BED_ROOM => self::BED_ROOM_DESC,
            self::DOUBLE_ROOM => self::DOUBLE_ROOM_DESC,
            self::FAMILY_ROOM => self::FAMILY_ROOM_DESC,
            self::BUSINESS_ROOM=>self::BUSINESS_ROOM_DESC

        ];
    }

    public static function getcheckInTypeItem($item){
        return array_get(self::getcheckInType(),$item);
    }

    public static function getToiletDesc(){
        return [
            self::PRIVATE_TOILET=>self::PRIVATE_TOILET_DESC,
            self::PRIVATE_TOILET=>self::PUBILC_TOILET_DESC
        ];
    }
    public static function getToiletItem($item){
        return array_get(self::getToiletDesc(),$item);
    }

    public static function getBedDesc(){
        return [
            self::EVERY_GUEST=>self::EVERY_GUEST_DESC,
            self::EVERY_DAY=>self::EVERY_DAY_DESC
        ];
    }

    public static function getBedItem($item){
        return array_get(self::getBedDesc(),$item);
    }
}