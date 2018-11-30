<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/29/18
 * Time: 6:10 PM
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;

class Votes extends Command{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Votes';

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
        $this->server->on('Task', [$this, 'onTask']);//在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务
        $this->server->on('Finish', [$this, 'onFinish']);
        $this->server->start();
    }
    // 响应函数
    public function onRequest(\swoole_http_server $server,\swoole_http_request $request,\swoole_http_response $response) {
        try {
            $data = isset($request->get) ? $request->get : '';
            if (empty($data)) {
                throw new Exception('没有传递参数', 422);
            }
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            if (0 == $redis->hGet('vote_user_number:' . $data['vote_id'], $data['open_id'])) {
                throw new Exception('今日投票数已用完', 503);
            }
            if ($redis->exists('option_today_proof:' . $data['option_id'] . ':' . $data['open_id'])) {
                throw new Exception('今日已投过该选项', 405);
            }
            // 减少用户今日投票数
            $redis->hIncrBy('vote_user_number:' . $data['vote_id'], $data['open_id'], -1);
            // 创建用户和选项今日的已投票凭证
            $exp = Time::today()[1] - time();
            $redis->set('option_today_proof:' . $data['option_id'] . ':' . $data['open_id'], 1, $exp);
            // 增加所有投票的总票数
            $redis->incrBy('totalVoteCount', 1);
            // 增加指定投票的所有选项总票数
            $redis->incrBy($data['vote_id'] . 'OfVoteCount', 1);
            // 增加选项的投票数
            $redis->zIncrBy('vote: '. $data['vote_id'] . ':option', 1, $data['option_id']);
            // 投递异步任务，使用异步完成投票记录写入 Redis 队列
            $data['create_time'] = time();
            $server->task(json_encode($data));
        } catch (Exception $exception) {
            $response->end(json_encode(['code' => $exception->getCode(), 'message' => $exception->getMessage()]));
        }
        $response->end(json_encode(['code' => 200, 'message' => '操作成功']));
    }
    // Worker/Task进程启动时回调函数
    public function onWorkerStart(\swoole_http_server $server,$workid) {
        if (0 == $workid) {
            // 启动 Timer 定时器,每5分钟回调一次 asyncWriteDatabase 函数
            swoole_timer_tick(1000 * 60 * 5, [$this, 'asyncWriteDatabase']);
        }
    }
    // 异步任务处理函数
    public function onTask(\swoole_http_server $server,$workid,$data){
        // 把每个用户的投票记录写入 Redis 队列
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->lPush('voteLogQueue', $data);
        return true;
    }
    // 异步任务完成通知
    public function Finish($timer_id, $params = null) {
    }

    // 定时器任务函数
    public function asyncWriteDatabase($timer_id, $params = null) {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        // 把 Redis 队列中的投票记录取出，写入到 MySQL 数据库
        $length = $redis->lLen('voteLogQueue');
        for ($i = 1; $i <= $length; $i++) {
            $log = json_decode($redis->rPop('voteLogQueue'), true);
            Db::name('vote_log')->insert($log);
        }
    }
}

