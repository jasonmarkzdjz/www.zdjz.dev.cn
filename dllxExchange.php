<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/8/22
 * Time: 15:00
 */

//direct 交换器类型
$ex_name = 'dlx.ex.water.directe';
$quene_name = 'dlx.quene.water.direct';
$route_key = 'dlx.water.direct';
$bind_key = 'dlx.water.direct';
$connect = new \AMQPConnection(array('host'=>'127.0.0.1','port'=>'5672','vhost'=>'/zdjz','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
$chann = new \AMQPChannel($connect);
//创建一个fonmant交换器
$ex = new \AMQPExchange($chann);
$ex->setName($ex_name);//交换器名称
$ex->setType(AMQP_EX_TYPE_DIRECT);//交换器类型
$ex->setFlags(AMQP_DURABLE);//是否持久化
//是否自动删除 当所有队列和交换机器绑定到当前交换器上不在使用时，
//是否自动删除交换器 true：删除false：不删除
$ex->setFlags(AMQP_AUTODELETE);
$ex->declareExchange();//声明交换器


$quene = new \AMQPQueue($chann);
$quene->setName($quene_name);
$quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$quene->setFlags(AMQP_DURABLE);//是否持久化
$quene->declareQueue();
$quene->bind($ex_name,$bind_key);
$ex->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key);