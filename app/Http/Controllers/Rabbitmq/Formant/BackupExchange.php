<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/8/20
 * Time: 17:03
 */

namespace app\Http\Controllers\Rabbitmq\Formant;

use App\Http\Controllers\Controller;
use Faker\Provider\Base;
use library\Service\MQ\BaseMQ;

class  BackupExchange extends Controller
{
    //备份交换器
    public function exec()
    {
        //备份交换器类型
        $exchangeName = 'alternate.exchange.news';
        $queneName = 'alternate.queue.news';
        $route_key = 'alternate.queue.news';
        $bind_key = 'alternate.queue.news';
        $mq = new BaseMQ($exchangeName,$queneName,$route_key,$bind_key,AMQP_EX_TYPE_FANOUT);
        $mq->declareExchange();
        $queue = $mq->declareQueue();
        $queue->bind($exchangeName,$bind_key);
        $mq->AMQPChannel->close();
        $mq->close();
    }
}
