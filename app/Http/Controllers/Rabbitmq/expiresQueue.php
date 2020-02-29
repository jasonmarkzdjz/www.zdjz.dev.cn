<?php


//死信交换器和队列
$dlxExchange = 'dlx.exchange.news';
$dlxQuene = 'dlx.queue.news';
$dlx_bind_key = 'dlx.queue.news';

$cacheExchangeName = 'cache.exchange.news';
$cacheQueneName = 'cache.queue.news';

$cacheBind_key = 'cache.queue.new';

$connect = new \AMQPConnection(array('host'=>'192.168.75.178','port'=>'5672','vhost'=>'/','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
$chann = new \AMQPChannel($connect);
//创建一个fonmant交换器
$cacheExchange = new \AMQPExchange($chann);
$cacheExchange->setName($cacheExchangeName);//交换器名称
$cacheExchange->setType(AMQP_EX_TYPE_FANOUT);//交换器类型
$cacheExchange->setFlags(AMQP_DURABLE);//是否持久化
$cacheExchange->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
$cacheExchange->declareExchange();//声明交换器


$quene = new \AMQPQueue($chann);
$quene->setName($cacheQueneName);
$quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$quene->setFlags(AMQP_DURABLE);//是否持久化
$quene->setArgument('x-expires',30000);//对每条消息设置过期时间
/*
 * 消息过期时间
 * x-message-ttl:队列属性设置过期时间 可以控制被 publish 到 queue 中的 message 被丢弃前能够存活的时间 服务器将努力在 TTL 到期或到期后的短时间内处理掉该 message 一旦消息过期，就会从队列中抹去
 * expiration：消息过期,不会立即从队列中抹去，因为每条消息是否过期是在投递消息的时候判断
 * x-expires: 队列超时：当前的queue在指定的时间内，没有消费者订阅就会被删除，以毫秒为单位
 *
 * */
$quene->setArgument('x-dead-letter-exchange',$dlxExchange);//通知死信交换器
$quene->setArgument('x-dead-letter-routing-key',$dlx_bind_key);//指定routkey
$quene->declareQueue();
$quene->bind($cacheExchangeName,$connmentBind_key);
$cacheExchange->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key,AMQP_MANDATORY,array('delivery_mode'=>2));
$chann->close();
?>