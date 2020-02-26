<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bls\Pano\Model\PanoModel;
use App\Bls\Pano\Model\PanoSceneModel;
use App\Bls\Pano\Model\TaskSceneModel;
use App\Bls\Pano\Model\TaskLogModel;
use App\Bls\Pano\CommonBls;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MqBasicGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Rabbitmq:basice-get';

    /**
     * The console command description.
     * 可以单条的获取消息
     * @var string
     */
    protected $description = '拉模式basice_get获取消息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ex_name = 'ex.zdjz.news';
        $quene_name = 'quene.zdjz.news';
        $bind_key = 'xian.zdjz.news';
        $connection = new \AMQPConnection(array('host'=>'127.0.0.1','port'=>'5672','vhost'=>'/zdjz','login'=>'guest','password'=>'guest'));
        if(!$connection->connect()) {
                echo "链接失败";
        }
        $chann = new  \AMQPChannel($connection);

        //创建一个fonmant交换器
        $ex = new \AMQPExchange($chann);
        $ex->setName($ex_name);//交换器名称
        $ex->setType(AMQP_EX_TYPE_FANOUT);//交换器类型
        $ex->setFlags(AMQP_DURABLE);//是否持久化
        $ex->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除

        $quene = new \AMQPQueue($chann);
        $quene->setName($quene_name);
        $quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
        $quene->setFlags(AMQP_DURABLE);//是否持久化
        $quene->bind($ex_name,$bind_key);
        $message = $quene->get();
        $body = $message->getBody();
        var_dump($message);
        if($message['body']){
            //确认消息
            echo $message['body'].'';
            $quene->ack($message['delivery_tag']);
        }


        $chann->close();
        $connection->close();
    }
}
