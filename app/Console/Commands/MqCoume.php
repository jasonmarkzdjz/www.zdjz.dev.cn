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

class MqCoume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推模式consume持续订阅消息';

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
        $connection = new AMQPStreamConnection('192.168.75.172', 5672, 'bitch', 'bitch');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, true);

        $receiver = new self();
        $channel->basic_consume('hello', '', false, true, false, false, [$receiver, 'callFunc']);

        while(true) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }

    public function callFunc($msg) {
        $content = json_decode($msg->body,true);
    }
}
