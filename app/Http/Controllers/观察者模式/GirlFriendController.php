<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:10
 */
namespace App\Http\Controllers\观察者模式;
use App\Http\Controllers\Controller;
class GirlFriendController extends Controller{
    public function frozenAction(){
        echo "你的男盆友正在花钱 .冻结他的银行卡";
    }
}