<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:30
 */
namespace app\Http\Controllers\Factory\适配器模式;
use App\Http\Controllers\Controller;
use app\Http\Controllers\Factory\Interfacer\PersonMan;
class AdaptorController extends Controller implements PersonMan {
    /**
     *
     * 初始化保存一个适配器对象
     *
     * 初始化一个构造函数
     * */
    protected  $wife;

    public function __construct($wife)
    {
        $this->wife = $wife;
    }
    public function cook()
    {
        $this->wife->cook();
    }
    public function writePhp()
    {
        // TODO: Implement writePhp() method.
        echo "我会写php代码";
    }
}