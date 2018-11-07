<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoFnConfigModel;

class PanoFnConfigBls
{

    /**
     * 获取PANO功能配置列表
     * @author Jason7 2018-07-02
     * @param  integer $enabled 1：启用 0：禁用
     * @param  string  $sort    排序值 asc desc
     * @return stdclass
     */
    public function getFnConfigList($enabled = 1, $sort = 'asc')
    {
        return PanoFnConfigModel::where('fstatus', $enabled)->orderBy('fsort', $sort)->get();
    }
}