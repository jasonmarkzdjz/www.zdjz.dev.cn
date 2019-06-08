<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:32
 */

namespace app\Http\Controllers\适配器模式;
use App\Http\Controllers\Controller;
class WifeController extends Controller {
    /**
     *已可将一个类的接口转换成客户希望的另外一个接口。使得原本不兼容的接口能够一起工作。
    通俗的理解就是讲不通的接口适配成统一的接口
     *
     * 已知的类不方便在修改类里面的代码
     * */
    public function cook(){
        echo "我会做满汉全席";
    }
}
