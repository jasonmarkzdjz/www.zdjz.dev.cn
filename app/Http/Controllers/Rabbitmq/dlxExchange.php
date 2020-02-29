<?php


//备份交换器和队列
$dlx_exchange = 'dlx.exchange.news';
$dlx_quene = 'dlx.queue.news';
$dlx_bind_key = 'dlx.queue.news';

$route_key = 'dlx.exchange.news';

$connect = new \AMQPConnection(array('host'=>'192.168.75.178','port'=>'5672','vhost'=>'/','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
//死信交换器和死信队列
$dlxChann = new \AMQPChannel($connect);
$dlxExchange = new \AMQPExchange($dlxChann);
$dlxExchange->setName($dlx_exchange);//交换器名称
$dlxExchange->setType(AMQP_EX_TYPE_FANOUT);//备份交换器的交换器类型设置为fanout
$dlxExchange->setFlags(AMQP_DURABLE);//是否持久化
$dlxExchange->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
$dlxExchange->declareExchange();//声明交换器
$dlxQuene = new \AMQPQueue($dlxChann);
$dlxQuene->setName($dlx_quene);
$dlxQuene->setFlags(AMQP_DURABLE);//是否持久化
$dlxQuene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$dlxQuene->declareQueue();
$dlxQuene->bind($dlx_exchange,$dlx_bind_key);//备份交换器通过bindKey与队列进行绑定


$dlxExchange->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),'123456',AMQP_MANDATORY,array('delivery_mode'=>2));

$dlxChann->close();
?>