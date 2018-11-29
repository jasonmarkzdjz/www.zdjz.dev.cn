<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/29/18
 * Time: 6:10 PM
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;

class Timer extends Command{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Timer:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'dingshiqi';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $server;

    public function __construct() {
        parent::__construct();
    }
    //websocket fuwuqi
    public function handle(){
        $this->server = new \swoole_http_server("0.0.0.0", 9502);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']); #在worker进程内监听一个Server端口
        $this->server->on('Request', [$this, 'onRequest']);#ji
        $this->server->on('Task', [$this, 'onTask']);
        $this->server->on('Finish', [$this, 'onFinish']);
        $this->server->start();
    }
    // 响应函数
    public function onRequest(\swoole_http_server $server,\swoole_http_request $request,\swoole_http_response $response) {

    }
    // Worker/Task进程启动时回调函数
    public function onWorkerStart(\swoole_http_server $server,$workid) {

    }
    // 异步任务处理函数
    public function onTask(\swoole_http_server $server,$workid){

    }
    // 异步任务完成通知
    public function Finish() {

    }

}

