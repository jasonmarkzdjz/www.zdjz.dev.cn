<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:44
 */

namespace App\Http\Controllers\策略模式;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\策略模式\LoveInterface;

class KeAiController extends Controller implements LoveInterface {


    public function sajiao()
    {
        echo "可爱行的撒娇";
        // TODO: Implement sajiao() method.
    }
}