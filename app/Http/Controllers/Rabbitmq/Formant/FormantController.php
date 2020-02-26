<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/12/8
 * Time: 12:19
 */

namespace app\Http\Controllers\Rabbitmq\Formant;

use App\Http\Controllers\Controller;
use library\Service\Common\CacheConst;
use library\Service\MQ\BaseMQ;

class  FormantController extends Controller
{

    /***
     * x-message-ttl:设置队列TTL 属性的方法，一旦消息过期，就会从队列中抹去
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     * @throws \AMQPQueueException
     */
    public function exec()
    {
        //可以当做是一个备份交换器
        $route_key = 'alternate.exchange.news';
        $bind_key = 'xian.zdjz.news';
        $exchangeName = 'alternate.exchange.news';
        $queneName = 'alternate.queue.news';
        $mq = new BaseMQ($exchangeName,$queneName,$route_key,$bind_key,AMQP_EX_TYPE_DIRECT);
        $exchange = $mq->declareExchange();
        $queue = $mq->declareQueue();
//        $queue->setArguments(['alternate-exchange' => 'dead.news.formant','x-message-ttl' => 6000]);
        //设置死信队列  消息过期 消息被拒绝 队列长度限制
//        $queue->setArgument('x-message-ttl',6000);//通过设置队列属性设置消息过期时间为6秒
//        $mq->AMQPQueue->setArgument('alternate-exchange', 'dead.news.formant');//通知死信交换器   alternate-exchange 备份交换器
//        $mq->AMQPQueue->setArgument('x-dead-letter-routing-key', 'dead.news.formant');//指定routkey
        $mq->bindQueue();
        $exchange->publish(serialize(array('code'=>true,'message'=>'西安欢迎您'.rand(99,99999))),$route_key,AMQP_MANDATORY,['delivery_mode'=>2]);
        $mq->AMQPChannel->close();
        $mq->close();
    }
}