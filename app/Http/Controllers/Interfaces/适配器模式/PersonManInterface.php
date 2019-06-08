<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/6/8
 * Time: 19:31
 */
namespace App\Http\Controllers\Factory\适配器模式;
use App\Http\Controllers\Controller;
interface PersonMan {
    public function cook();
    public function writePhp();
}