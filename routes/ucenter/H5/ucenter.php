<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/19
 * Time: 15:26
 */


Route::group(['prefix'=>'/ucenter','middleware'=>['ucenter.auth']],function() {
    Route::get('/logout',['uses'=>'Ucenter\\H5\\User\\UserController@logOut','as'=>'ucenter.user.logout']);//登录退出
    Route::POST('/storelogin',['uses'=>'Ucenter\\H5\\User\\UserController@login','as'=>'ucenter.user.login']);//登录
    Route::get('/rechange',['uses'=>'Ucenter\\H5\\User\\UserController@getRechange','as'=>'ucenter.user.rechange']);//充值流水
    Route::get('/collect',['uses'=>'Ucenter\\H5\\User\\UserController@getCollection','as'=>'ucenter.user.collect']);//用户收藏
    Route::Match(['get','post'],'/register',['uses'=>'Ucenter\\H5\\User\\UserController@register','as'=>'ucenter.user.register']);
});
Route::get('/ucenter/login', ["uses" => 'Ucenter\\H5\\User\\UserController@Login','as'=>'ucenter.h5.user.login']);
Route::get('/ucenter/refund',['uses'=>'Ucenter\\H5\\User\\UserController@refundApply','as'=>'ucenter.user.refund']);//用户发起退款申请