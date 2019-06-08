<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 18:13
 */
namespace app\Http\Controllers\工厂模式;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Interfaces\工厂模式\SkillInterface;

class JinLingController extends Controller implements SkillInterface{



    public function family()
    {
        echo "精灵在伐木";
        // TODO: Implement family() method.
    }

    public function buy()
    {
        echo  "精灵在使用精灵币";
        // TODO: Implement buy() method.
    }
}