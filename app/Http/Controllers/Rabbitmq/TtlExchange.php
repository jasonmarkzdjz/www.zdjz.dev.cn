<?php


//备份交换器和队列
$alter_nate_exchange = 'alternate.exchange.news';
$alter_nate_quene = 'alternate.queue.news';
$alter_nage_bind_key = 'alternate.queue.news';

$conmmentExchangeName = 'conmment.exchange.news';
$conmmentQueneName = 'conmment.queue.news';

$connmentBind_key = 'comment.queue.new';

$connect = new \AMQPConnection(array('host'=>'192.168.75.178','port'=>'5672','vhost'=>'/','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
$chann = new \AMQPChannel($connect);
//创建一个fonmant交换器
$commentExchange = new \AMQPExchange($chann);
$commentExchange->setName($conmmentExchangeName);//交换器名称
$commentExchange->setType(AMQP_EX_TYPE_FANOUT);//交换器类型
$commentExchange->setFlags(AMQP_DURABLE);//是否持久化
$commentExchange->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
$commentExchange->declareExchange();//声明交换器


$quene = new \AMQPQueue($chann);
$quene->setName($conmmentQueneName);
$quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$quene->setFlags(AMQP_DURABLE);//是否持久化
$quene->setArgument('x-message-ttl',3000);//队列属性设置消息过期
//过期时间 x-message-ttl 队列属性设置过期时间  可以控制被 publish 到 queue 中的 message 被丢弃前能够存活的时间 服务器将努力在 TTL 到期或到期后的短时间内处理掉该 message 一旦消息过期，就会从队列中抹去
//x-expires:对消息这是过期时间 到期之后消息自动消失 即使消息过期，也不会马上从队列中抹去，因为每条消息是否过期时在即将投递到消费者之前判定的
$quene->setArgument('x-dead-letter-exchange',$alter_nate_exchange);//通知死信交换器
$quene->setArgument('x-dead-letter-routing-key',$alter_nage_bind_key);//指定routkey
$quene->declareQueue();
$quene->bind($conmmentExchangeName,$connmentBind_key);
$commentExchange->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key,0,array('delivery_mode'=>2));
$chann->close();
?>