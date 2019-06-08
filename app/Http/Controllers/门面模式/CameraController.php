<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:55
 */

namespace App\Http\Controllers\门面模式;

use Magento\AdminGws\Model\Controllers;

class CameraController extends Controllers{

    public function active(){

        echo "打开照相机";
    }

    public function deactive(){

        echo "关闭照相机";
    }

}