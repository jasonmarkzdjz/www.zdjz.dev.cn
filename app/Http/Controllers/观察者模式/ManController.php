<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:08
 */
namespace App\Http\Controllers\观察者模式;
use App\Http\Controllers\Controller;

/**
 *
 * @author Jason
 * @desc:观察者模式 需要两个类 观察类和被观察者
 * @desc:观察者A观察主题B 如果B如果发生变化 则观察者收到通知 执行相应的动作
 * 男人类 用来存储观察者 比如 new 两个观察者对象 小丽和小花
 * 小丽和小花是观察者 观察他男朋友 如果发现他男盆友花钱就冻结他的银行卡
 * */
class ManController extends Controller {
    protected $observers = [];
    /**
     * 添加观察者方法
     * */
    public function addServers($observer){
        $this->observers[] = $observer;
    }
    /**
     * 删除观察者
     *
     * */
    public function deServers($observer){
        //查找数组中的建
        $key = array_search($observer,$this->observers);
        //根据建删除值 并且数组重新索引
        array_splice($this->observers,$key,1);
    }
    /**
     * 花钱的方法 当观察者观察到花钱的方法 则 观察者立马冻结银行卡
     * */
    public function buy(){
        //当被观察者做出这个花钱的行为的时候 观察者得到通知并且做出一定的响应
        foreach ($this->observers as $o=>$girl){
            $girl->frozenAction();
        }
    }
}
