<?php


//direct 交换器类型
$ex_name = 'a.ttl.ex.drug.direct';
$quene_name = 'a.ttl.quene.drug.direct';
$route_key = 'a.ttl.xian.drug.direct';
$bind_key = 'a.ttl.xian.drug.direct';
$connect = new \AMQPConnection(array('host'=>'127.0.0.1','port'=>'5672','vhost'=>'/','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
$chann = new \AMQPChannel($connect);
//创建一个fonmant交换器
$ex = new \AMQPExchange($chann);
$ex->setName($ex_name);//交换器名称
$ex->setType(AMQP_EX_TYPE_DIRECT);//交换器类型
$ex->setFlags(AMQP_DURABLE);//是否持久化
$ex->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
$ex->declareExchange();//声明交换器


$quene = new \AMQPQueue($chann);
$quene->setName($quene_name);
$quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$quene->setFlags(AMQP_DURABLE);//是否持久化
//设置死信队列  消息过期 消息被拒绝 队列长度限制
$quene->setArgument('x-message-ttl',30000);
//过期时间 x-message-ttl 队列属性设置过期时间  可以控制被 publish 到 queue 中的 message 被丢弃前能够存活的时间 服务器将努力在 TTL 到期或到期后的短时间内处理掉该 message
//一旦消息过期，就会从队列中抹去
// x-expires:对消息这是过期时间 到期之后消息自动消失 即使消息过期，也不会马上从队列中抹去，因为每条消息是否过期时在即将投递到消费者之前判定的
$quene->setArgument('x-dead-letter-exchange','dlx.ex.drug.directe');//通知死信交换器
$quene->setArgument('x-dead-letter-routing-key','dlx.water.direct');//指定routkey
$quene->declareQueue();
$quene->bind($ex_name,$bind_key);
$ex->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key,0,array('delivery_mode'=>2));



?>