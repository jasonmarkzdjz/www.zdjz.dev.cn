<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:42
 */

namespace App\Http\Controllers\工厂方法;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\工厂方法\FactoryInterface;

class XiaoMiFactoryController extends Controller implements FactoryInterface{


    static function createPhpne()
    {
        // TODO: Implement createPhpne() method.
        return new XiaoMiController();
    }
}