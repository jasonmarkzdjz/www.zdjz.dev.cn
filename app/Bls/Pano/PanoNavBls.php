<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoNavModel;
use Auth;
class PanoNavBls
{
    /**
     * 获取全景导航列表
     * @author Jason7 2018-05-25
     * @return stdclass
     */
    public function getNavList()
    {
        return PanoNavModel::where('merchantId',Auth::user()->id)->get();
    }

    /**
     * 删除全景分类
     * @author Jason7 2018-05-25
     * @param  int $id 
     * @return int
     */
    public function deleteNav($id)
    {
        return PanoNavModel::where('merchantId',Auth::user()->id)->where('id',$id)->delete();
    }
}