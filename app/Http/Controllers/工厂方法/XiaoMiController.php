<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:31
 */

namespace App\Http\Controllers\工厂方法;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\工厂方法\FactoryFuncInterface;

class XiaoMiController extends Controller implements FactoryFuncInterface{

    public function call()
    {
        echo "我正在用小米手机打电话";
        // TODO: Implement call() method.
    }

    public function receive()
    {
        echo "我正在用小米手机接电话";
        // TODO: Implement receive() method.
    }
}