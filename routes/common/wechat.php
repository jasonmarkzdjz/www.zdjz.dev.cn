<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/6
 * Time: 10:21
 */

Route::get('/wechat/checktoken',['uses'=>'Oauth\WechatController@checkWechat','as'=>'wechat.checktoken']);