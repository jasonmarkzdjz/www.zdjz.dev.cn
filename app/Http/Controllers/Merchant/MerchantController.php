<?php

namespace App\Http\Controllers\Merchant;

use App\Bls\Merchant\MerchantBls;
use App\Bls\Merchant\Model\AccountModel;
use App\Bls\Merchant\Model\AreaModel;
use App\Bls\Merchant\Model\MerchantModel;
use App\Bls\Merchant\Model\PayConfigModel;
use App\Bls\Merchant\Model\UserprofileModel;
use App\Bls\Merchant\Model\PersonauthModel;
use App\Bls\Merchant\Model\CompanyauthModel;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use app\Http\Controllers\Factory\适配器模式\AdaptorController;
use App\Http\Controllers\工厂方法\HuaweiFactoryController;
use App\Http\Controllers\工厂方法\XiaoMiFactoryController;
use App\Http\Controllers\工厂模式\FactoryController;
use App\Http\Controllers\策略模式\GirlFrendController;
use App\Http\Controllers\策略模式\KeAiController;
use app\Http\Controllers\适配器模式\WifeController;
use App\Http\Controllers\门面模式\FacdeController;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use library\Service\Contst\Common\StatusConst;
use library\Service\Contst\Common\UserTypeConst;
use library\Service\Contst\PayConst;
use library\Service\File\TMFile;
use library\Service\Response\JsonResponse;
use Redis;


class MerchantController extends Controller {


    /**
     * @author jason
     * @desc 商户登录入口
     * @date 2018/05/20
     * */
    public function merchantLogin() {
//     echo "欢迎您。".rand(1000,9999);exit;
//
//      //静态工厂模式
//        $person = FactoryController::createFactory('person');
//        $jinling = FactoryController::createFactory('jinling');
//        //工厂方法
//        $xiaomi = XiaoMiFactoryController::createPhpne();
//        $huawei = HuaweiFactoryController::createPhpne();
//
//        //门面模式
//        $facde = new FacdeController();
//        $facde->start();
//        //适配器模式
//        $wife = new WifeController();
//        $adaptor = new AdaptorController($wife);
//        $adaptor->cook();
//        $adaptor->writePhp();
//        //策略模式
//        $keai = new KeAiController();
//        $girlfrend = new GirlFrendController($keai);
//        $girlfrend->sajiao();
        $ex_name = 'ex.zdjz.news';
        $quene_name = 'quene.zdjz.news';
        $route_key = 'xian.zdjz.news';
        $bind_key = 'xian.zdjz.news';
        $connect = new \AMQPConnection(array('host'=>'127.0.0.1','port'=>'5672','vhost'=>'/zdjz','login'=>'guest','password'=>'guest'));
        if(!$connect->connect()){
            echo "mq连接失败";
        }
        $chann = new \AMQPChannel($connect);

        //创建一个fonmant交换器
        $ex = new \AMQPExchange($chann);
        $ex->setName($ex_name);//交换器名称
        $ex->setType(AMQP_EX_TYPE_FANOUT);//交换器类型
        $ex->setFlags(AMQP_DURABLE);//是否持久化
        $ex->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
        $ex->declareExchange();//声明交换器

        $quene = new \AMQPQueue($chann);
        $quene->setName($quene_name);
        $quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
        $quene->setFlags(AMQP_DURABLE);//是否持久化
        $quene->declareQueue();

        $quene->bind($ex_name,$bind_key);

        $ex->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key);


    //direct 交换器类型
    $ex_name = 'ex.zdjz.house.direct';
        $quene_name = 'quene.zdjz.house.direct';
        $route_key = 'xian.zdjz.house.direct';
        $bind_key = 'xian.zdjz.house.direct';
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
        $ex->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
        $ex->declareExchange();//声明交换器
        $quene = new \AMQPQueue($chann);
        $quene->setName($quene_name);
        $quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
        $quene->setFlags(AMQP_DURABLE);//是否持久化
        $quene->declareQueue();
        $quene->bind($ex_name,$bind_key);
        $ex->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key);


        //direct 交换器类型
        $ex_name = 'ex.zdjz.tvplay.topic';
        $quene_name = 'quene.zdjz.tvplay.topic';
         $bind_key = '*.*.tvplay';
        $route_key = 'xian.sd.tvplay';
        $connect = new \AMQPConnection(array('host'=>'127.0.0.1','port'=>'5672','vhost'=>'/zdjz','login'=>'guest','password'=>'guest'));
        if(!$connect->connect()){
            echo "mq连接失败";
        }
        $chann = new \AMQPChannel($connect);
        //创建一个fonmant交换器
        $ex = new \AMQPExchange($chann);
        $ex->setName($ex_name);//交换器名称
        $ex->setType(AMQP_EX_TYPE_TOPIC);//交换器类型
        $ex->setFlags(AMQP_DURABLE);//是否持久化
        $ex->setFlags(AMQP_AUTODELETE);//是否自动删除  当所有队列和交换机器绑定到当前交换器上不在使用时，是否自动删除交换器 true：删除false：不删除
        $ex->declareExchange();//声明交换器
        $quene = new \AMQPQueue($chann);
        $quene->setName($quene_name);
        $quene->setFlags(AMQP_AUTODELETE);//是否自动删除  当前队列上没有订阅的消费者时 自动删除
        $quene->setFlags(AMQP_DURABLE);//是否持久化
        $quene->declareQueue();
        $quene->bind($ex_name,$bind_key);
        $ex->publish(json_encode(array('code'=>1,'message'=>'西安欢迎你!'.rand(99,99999))),$route_key);
     }
}
