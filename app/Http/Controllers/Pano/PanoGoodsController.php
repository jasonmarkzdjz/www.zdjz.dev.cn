<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\CommonBls;
class PanoGoodsController extends Controller
{

    /**
     * Ajax标签列表
     * @author Jason7 2018-06-08
     * @return array
     */
    public function list(Request $request)
    {
        $keywords = $request->input('keywords');

        $CommonBls = new CommonBls();
        $list = $CommonBls->get_goods_list(2,$keywords);
        return $list;
    }
}
