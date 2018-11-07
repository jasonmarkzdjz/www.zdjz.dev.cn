<?php
namespace App\Bls\Pano;

use App\Bls\Pano\Model\PanoCateModel;
use Auth;
class PanoCateBls
{

    /**
     * 获取全景分类列表
     * @author Jason7 2018-05-25
     * @return stdclass
     */
    public function getCateList($sort = 'desc')
    {
        return PanoCateModel::where('merchantId',Auth::user()->id)->orderBy('cateSort',$sort)->get();
    }

    /**
     * 删除全景分类
     * @author Jason7 2018-06-21
     * @param  int $id
     * @return int
     */
    public function deleteCate($id = 0)
    {
        return PanoCateModel::where('merchantId',Auth::user()->id)->where('id',$id)->delete();
    }
}