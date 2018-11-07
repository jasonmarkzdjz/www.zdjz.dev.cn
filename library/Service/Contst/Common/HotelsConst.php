<?php
namespace library\Service\Contst\Common;


class HotelsConst
{

    const S_ROOM = 1;
    const B_ROOM = 2;
    const D_ROOM = 3;
    const E_ROOM = 4;
    const H_ROMM = 5;

    const S_ROOM_DESC = '单人房';
    const B_ROOM_DESC = '大床房';
    const D_ROOM_DESC = '双床房';
    const E_ROOM_DESC = '家庭房';
    const H_ROMM_DESC = '商务房';
    /**
     * 获取所有状态信息
     * @return string[]
     */
    public static function desc(){
        return [
            self::S_ROOM => self::S_ROOM_DESC,
            self::B_ROOM => self::B_ROOM_DESC,
            self::D_ROOM => self::D_ROOM_DESC,
            self::E_ROOM => self::E_ROOM_DESC,
            self::H_ROMM => self::H_ROMM_DESC
        ];
    }

    public static function getDescByItem($item)
    {
        return array_get(self::desc(), $item);
    }
}