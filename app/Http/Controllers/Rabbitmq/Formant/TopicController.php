<?php
namespace app\Http\Controllers\Rabbitmq\Formant;

use App\Http\Controllers\Controller;
use library\Service\MQ\BaseMQ;

class  TopicController extends Controller
{
    public function exec()
    {
        $exchangeName = 'topic.zdjz.tvplay';
        $queneName = 'topic.quene.zdjz.tvplay';
        $bind_key = '*.xian.tvplay';
        $route_key = 'topic.xian.tvplay';

        $mq = new BaseMQ($exchangeName,$queneName,$route_key,$bind_key,AMQP_EX_TYPE_TOPIC);
        $mqExchange = $mq->ceratExchange();
        $mq->bindQueue();
        $mqExchange->publish(json_encode(array('code' => 1, 'message' => '西安欢迎你!' . rand(99, 99999))), $route_key, AMQP_MANDATORY, ['delivery_mode' => 2]);
        $mq->AMQPChannel->close();
        $mq->close();
    }
}