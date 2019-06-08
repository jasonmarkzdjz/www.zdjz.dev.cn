<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:04
 */
namespace app\Http\Controllers\工厂模式;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\工厂模式\SkillInterface;


class PersonController extends Controller implements SkillInterface {



    public function  family()
    {
        echo "人族在辛辛苦苦的伐木";
        // TODO: Implement family() method.
    }

    public function buy()
    {
        echo "人族在使用人民币买房子";
        // TODO: Implement buy() method.
    }

}