<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:55
 */

namespace App\Http\Controllers\门面模式;

use Magento\AdminGws\Model\Controllers;

class LightController extends Controllers{

    public function trunOn(){

        echo "打开闪光灯";
    }

    public function turnOff(){

        echo "关闭闪光灯";
    }

}