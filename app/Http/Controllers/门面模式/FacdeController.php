<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:59
 */

namespace App\Http\Controllers\门面模式;

use Magento\AdminGws\Model\Controllers;

class FacdeController extends Controllers {


    protected $light;
    protected $crema;

    public function __construct()
    {
        $this->light = new LightController();
        $this->crema = new CameraController();
    }

    public function start(){
        $this->light->trunOn();
        $this->crema->active();
    }

    public function stop(){
        $this->light->turnOff();
        $this->crema->deactive();
    }
}