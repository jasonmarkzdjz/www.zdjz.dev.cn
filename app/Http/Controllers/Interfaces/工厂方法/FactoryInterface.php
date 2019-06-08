<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:38
 */

namespace App\Http\Controllers\Interfaces\工厂方法;

/*
 *
 *  工厂方法不负责对象的创建。具体的实现交给子类  工厂类之规定相应的接口
 *
 * */

interface FactoryInterface {


    static function createPhpne();
}