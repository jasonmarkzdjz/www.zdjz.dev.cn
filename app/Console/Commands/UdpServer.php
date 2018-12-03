<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/3/18
 * Time: 3:09 PM
 */
namespace  App\Console\Commands;

use Illuminate\Console\Command;

class UdpServer extends Command{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Udp';

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

    public function handle(){
        $this->server = new \swoole_server("0.0.0.0",9503,SWOOLE_PROCESS,SWOOLE_SOCK_TCP);
        $this->server->set(["worker_num"=>4,"log_file" => '/data/log/swoole.log',"daemonize" => false]);
//        $this->server->on("Start",[$this,"onStart"]);
        $this->server->on("Packet",[$this,"onPacket"]);
        $this->server->start();
    }

    public  function onPacket(\swoole_server $server,$data,$client_info){
        $server->sendto($client_info['address'],$client_info['port'],"server".$data);
    }
}