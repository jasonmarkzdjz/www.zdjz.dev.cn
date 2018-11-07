<?php
namespace App\Http\Controllers\Pano;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bls\Pano\PanoTagBls;
class PanoTagController extends Controller
{
    /**
     * Ajax标签列表
     * @author Jason7 2018-06-08
     * @return array
     */
    public function list()
    {
        $PanoTagBls = new PanoTagBls;
        $list = $PanoTagBls->getTagList();
        return $list->toArray();
    }
}
