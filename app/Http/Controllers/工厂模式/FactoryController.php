<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 17:51
 */
namespace App\Http\Controllers\工厂模式;

use App\Http\Controllers\Controller;

class FactoryController extends Controller {



    //静态工厂
    public static function createFactory($type) {

        switch ($type){
            case "person":
                return new PersonController();
                break;
            case "jinling":
                return new JinLingController();
                break;
            default:
                break;
        }
    }
}