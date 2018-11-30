<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/30/18
 * Time: 3:49 PM
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;

class Timer extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Timer';

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
        $this->server = new \swoole_server("0.0.0.0", 9502);
        $this->server->set(["worker_num"=>4,"daemonize"=>false]);
        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('Connect', [$this, 'onConnect']);//有新的连接进入时，在worker进程中回调
        $this->server->on('Receive', [$this, 'onReceive']);//接收到数据时回调此函数，发生在worker进程中
        $this->server->on('Close', [$this, 'onClose']);//TCP客户端连接关闭后，在worker进程中回调此函数
        $this->server->start();
    }
    /**
     * onStart事件在Master进程的主线程中被调用。在onStart中创建的全局资源对象不能在worker进程中被使用，因为发生onStart调用时，worker进程已经创建好了。
     *
     * */
    public function onStart(\swoole_server $server,$workid){
        echo "Start" . PHP_EOL;
    }
    /*
     *
     * 此事件在Worker进程/Task进程启动时发生
     * */
    public function onWorkerStart(\swoole_server $server,$workid){
        if(0 == $workid){
            // 启动 Timer，每 1000 毫秒回调一次 onTick 函数，
            swoole_timer_tick(1000, [$this, 'onTick']);
        }
    }
    // 定时任务函数
    public function onTick($timer_id, $params = null)
    {
        echo 'Hello' . PHP_EOL;
    }
    // 建立连接时回调函数
    public function onConnect(\swoole_server $server, $fd, $from_id)
    {
        echo "Connect" . PHP_EOL;
    }
    // 收到信息时回调函数
    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        echo "message: {$data} form Client: {$fd}" . PHP_EOL;
        // 将受到的客户端消息再返回给客户端
        $server->send($fd, "Message form Server: ".$data);
    }
    // 关闭连时回调函数
    public function onClose(\swoole_server $server, $fd, $from_id)
    {
        echo "Close" . PHP_EOL;
    }
}