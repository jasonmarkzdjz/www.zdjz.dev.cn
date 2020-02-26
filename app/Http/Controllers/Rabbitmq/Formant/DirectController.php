<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/12/8
 * Time: 12:21
 */

namespace app\Http\Controllers\Rabbitmq\Formant;

use App\Http\Controllers\Controller;
use library\Service\MQ\BaseMQ;

class  DirectController extends Controller
{
    public function exec()
    {
        $exchangeName = 'direct.xian.zdjz.news';
        $queneName = 'direct.xian.quene.zdjz.news';
        $route_key = 'direct.xian.zdjz.news';
        $bind_key = 'direct.xian.zdjz.news';
        $mq = new BaseMQ($exchangeName,$queneName,$route_key,$bind_key,AMQP_EX_TYPE_DIRECT);
        $exchange = $mq->declareExchange();
        //声明备份交换器
        $exchange->setArguments(['alternate-exchange'=>'alternate.exchange.news']);
        $mq->declareQueue();
        $mq->bindQueue();
        $exchange->publish(json_encode(array('code' => 1, 'message' => '西安欢迎你!' . rand(99, 99999))),$route_key, AMQP_MANDATORY ,['delivery_mode' => 2]);
        $mq->AMQPChannel->close();
        $mq->close();
    }
}


