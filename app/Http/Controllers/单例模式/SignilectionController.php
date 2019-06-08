<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:14
 */

namespace App\Http\Controllers\单例模式;
use Magento\AdminGws\Model\Controllers;

class SignilectionController extends Controllers {

    private function __construct(){
    }
    static private $instace;
    public static function getInstance(){
        if(self::$instance == null){
            self::$instance =  new self();
        }
        return self::$instance;
    }
}