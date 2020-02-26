<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/12/20
 * Time: 11:57
 */
namespace library\Service\MQ;


interface BaseMQInterface {

    public function close();

    public function declareExchange();

    public function bindQueue();

    public function declareQueue();

    public function envelope();
}