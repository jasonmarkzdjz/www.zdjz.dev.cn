<?php
namespace app\Http\Controllers\Rabbitmq\Formant;

use App\Http\Controllers\Controller;
use library\Service\MQ\BaseMQ;

class  DeadTtlExchangeController extends Controller
{
    public function exec()
    {

        $exchangeName = 'dead.news.formant';
        $queneName = 'dead.news.formant';
        $route_key = 'dead.news.formant';
        $bind_key = 'dead.news.formant';

        $mq = new BaseMQ($exchangeName,$queneName,$route_key,$bind_key,AMQP_EX_TYPE_FANOUT);
        $mqExchange = $mq->ceratExchange();
        $mq->bindQueue();
        $mqExchange->publish(json_encode(array('code' => 1, 'message' => '西安欢迎你!' . rand(99, 99999))), $route_key, AMQP_MANDATORY, array('delivery_mode' => 2));
        $mq->AMQPChannel->close();
        $mq->close();
    }
}

?>