<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/5
 * Time: 21:05
 */

namespace App\Http\Controllers\Common;

use App\Bls\Common\Model\AreaModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AreaController extends Controller{

    public function __construct(){

        // $this->middleware('merchant.groupauth');
    }
    /**
     * 地区联动
     */
    public function getArea(Request $request) {
        if ($request->isMethod('post')) {
            return AreaModel::where('superiorId',$request->post('suid'))->get();
        }
        return AreaModel::where('superiorId','')->get();
    }

}