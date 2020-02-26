<?php


//备份交换器和队列
$alter_nate_exchange = 'alternate.exchange.news';
$alter_nate_quene = 'alternate.queue.news';
$alter_nage_bind_key = 'alternate.queue.news';

//点赞记录交换器和和队列
$voteExchangeName = 'vote.exchange.news';
$voteQueueName = 'vote.exchange.queue';
$voteBindKey ='vote.exchange.news';



$route_key = 'vote.exchange.news';

$connect = new \AMQPConnection(array('host'=>'192.168.75.178','port'=>'5672','vhost'=>'/','login'=>'guest','password'=>'guest'));
if(!$connect->connect()){
    echo "mq连接失败";
}
$voteChann = new \AMQPChannel($connect);

$voteExchange = new \AMQPExchange($voteChann);
$voteExchange->setName($voteExchangeName);//交换器名称
$voteExchange->setType(AMQP_EX_TYPE_DIRECT);//交换器类型
$voteExchange->setFlags(AMQP_DURABLE);//是否持久化
$voteExchange->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
$voteExchange->setArgument('alternate-exchange',$alter_nate_exchange);//声明备份交换器
$voteExchange->declareExchange();//声明交换器

$voteQuene = new \AMQPQueue($voteChann);
$voteQuene->setName($voteQueueName);
$voteQuene->setFlags(AMQP_DURABLE);//是否持久化
$voteQuene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$voteQuene->declareQueue();
$voteQuene->bind($voteExchangeName,$voteBindKey);//备份交换器和队列进行绑定

$alterNateChann = new \AMQPChannel($connect);
$alternateExchange = new \AMQPExchange($alterNateChann);
$alternateExchange->setName($alter_nate_exchange);//交换器名称
$alternateExchange->setType(AMQP_EX_TYPE_FANOUT);//交换器类型
$alternateExchange->setFlags(AMQP_DURABLE);//是否持久化
//$alternateExchange->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
$alternateExchange->declareExchange();//声明交换器
$alternateQuene = new \AMQPQueue($alterNateChann);
$alternateQuene->setName($alter_nate_quene);
$alternateQuene->setFlags(AMQP_DURABLE);//是否持久化
//$alternateQuene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
$alternateQuene->declareQueue();
$alternateQuene->bind($alter_nate_exchange,$alter_nage_bind_key);//备份交换器和队列进行绑定


$voteExchange->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),'123456',AMQP_MANDATORY,array('delivery_mode'=>2));

$alterNateChann->close();
$voteChann->close();
?>