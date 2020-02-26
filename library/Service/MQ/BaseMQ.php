<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/12/20
 * Time: 11:00
 */

namespace library\Service\MQ;

class BaseMQ implements BaseMQInterface {

    /** MQ Link
     * @var \AMQPConnection
     */
    public $AMQPConnection ;

    /** MQ Envelope
     * @var \AMQPEnvelope
     */
    public $AMQPEnvelope ;

    /** MQ Exchange
     * @var \AMQPExchange
     */
    public $AMQPExchange ;

    /** MQ Queue
     * @var \AMQPQueue
     */
    public $AMQPQueue ;

    /** MQ Channel
     * @var \AMQPChannel
     */
    public $AMQPChannel ;

    static private $instace;

    public $exchange_name;

    public $queue_name;

    public $route_key;

    public $bind_key;

    public $amqe_ex_type;

    public static function getInstance(){

        if(self::$instace == null){

            self::$instace =  new self();
        }
        return self::$instace;
    }


    public function __construct($exchange_name = null,$queue_name = null,$route_key = null,$bind_key = null,$amqe_ex_type = null)
    {
        $host  = env('RABBITMQ_HOST','127.0.0,1');
        $port = env('RABBITMQ_PORT','5672') ;
        $conf = env('RABBITMQ_VHOST','/');
        $login = env('RABBITMQ_LOGIN','guest');
        $password = env('RABBITMQ_PASSWORD','guest');
        $this->bind_key = $bind_key;
        $this->queue_name = $queue_name;
        $this->exchange_name = $exchange_name;
        $this->route_key = $route_key;
        $this->amqe_ex_type = $amqe_ex_type;
        $this->AMQPConnection = new \AMQPConnection(['host' => $host,'port' => $port,'conf' => $conf,'login'=>$login,'password' => $password ]);
        if (!$this->AMQPConnection->connect())
            throw new \AMQPConnectionException("Cannot connect to the broker!\n");
    }
    /**
     * close link
     */
    public function close()
    {
        $this->AMQPChannel->close();
        $this->AMQPConnection->disconnect();
    }

    /** Channel
     * @return \AMQPChannel
     * @throws \AMQPConnectionException
     */
    public function channel()
    {
        if(!$this->AMQPChannel) {
            $this->AMQPChannel = new \AMQPChannel($this->AMQPConnection);
        }
        return $this->AMQPChannel;
    }


    /** Exchange
     * @return \AMQPExchange
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     */
    public function declareExchange()
    {

        if(!$this->AMQPExchange) {
            $this->AMQPExchange = new \AMQPExchange($this->channel());
            $this->AMQPExchange->setName($this->exchange_name);//交换器名称
            $this->AMQPExchange->setType($this->amqe_ex_type);//交换器类型
            $this->AMQPExchange->setFlags(AMQP_DURABLE);//是否持久化
            $this->AMQPExchange->declareExchange();
        }
        return $this->AMQPExchange ;
    }

    /** queue
     * @return \AMQPQueue
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function declareQueue()
    {
        if(!$this->AMQPQueue) {
            $this->AMQPQueue = new \AMQPQueue($this->channel());
            $this->AMQPQueue->setName($this->queue_name);
            $this->AMQPQueue->setFlags(AMQP_DURABLE);//是否持久化
            $this->AMQPQueue->declareQueue();
            //交换器和队列进行绑定
        }
        return $this->AMQPQueue;
    }


    public function bindQueue($exchangeName = '',$routing_key = '',$arguments = []) {

        return $this->declareQueue()->bind(!$exchangeName? $this->exchange_name:$exchangeName,
            !$routing_key?$this->route_key:$routing_key,$arguments);
    }

    /** Envelope
     * @return \AMQPEnvelope
     */
    public function envelope()
    {
        if(!$this->AMQPEnvelope) {
            $this->AMQPEnvelope = new \AMQPEnvelope();
        }
        return $this->AMQPEnvelope;
    }
}