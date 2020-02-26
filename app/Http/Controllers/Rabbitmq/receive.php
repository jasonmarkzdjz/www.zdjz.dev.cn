<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/12/8
 * Time: 19:57
 */
$ex_name = 'direct.zdjz.news';
$quene_name = 'direct.quene.zdjz.news';
$route_key = 'direct.xian.zdjz.news';
$bind_key = 'direct.xian.zdjz.news';
$connect = new \AMQPConnection(array('host'=>'192.168.75.175','port'=>'5672','vhost'=>'/zdjz','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
$chann = new \AMQPChannel($connect);
//创建一个topic交换器
$ex = new \AMQPExchange($chann);
$ex->setName($ex_name);//交换器名称
$ex->setType(AMQP_EX_TYPE_TOPIC);//交换器类型
$ex->setFlags(AMQP_DURABLE);//是否持久化


$quene = new \AMQPQueue($chann);
$quene->setName($quene_name);
$quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$quene->setFlags(AMQP_DURABLE);//是否持久化
$quene->bind($ex_name,$bind_key);


while (true) {
    $quene->consume(function ($envelope, $queue) {
        $msg = $envelope->getBody();
        echo $msg . '<br>';
        $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
    }, AMQP_DURABLE,$envelope->getDeliveryTag());
}