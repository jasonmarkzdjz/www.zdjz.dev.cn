<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/5
 * Time: 21:53
 */
//账户中心
Route::group(['prefix'=>'/', 'middleware'=>['merchant.groupauth']], function(){

    Route::group(['prefix'=>'/finance'],function () {


    Route::get('/', ['uses' => 'Account\AccountController@perAccount']);

    //账户明细
    Route::match(['get', 'post'], '/peraccount', ['uses' => 'Account\AccountController@perAccount', 'as' => 'account.peraccount']);

    //我的钱包
    Route::match(['get', 'post'], '/mywallet', ['uses' => 'Account\AccountController@myWallet', 'as' => 'account.mywallet']);

    //用户充值
    Route::match(['get', 'post'], '/recharge', ['uses' => 'Account\AccountController@recHarge', 'as' => 'account.recharge']);

    //充值回调
    Route::match(['get', 'post'], '/paycalbank', ['uses' => 'Account\AccountController@payCalbank', 'as' => 'account.paycalbank']);

    //设只支付密码获取短信验证码
    Route::match(['get', 'post'], '/paypwd', ['uses' => 'Account\AccountController@payPwd', 'as' => 'account.paypwd']);

    //支付密码的设置
    Route::match(['get', 'post'], '/payset', ['uses' => 'Account\AccountController@paySet', 'as' => 'account.payset']);

    //设置银行卡号
    Route::match(['get', 'post'], '/setbank', ['uses' => 'Account\AccountController@setBank', 'as' => 'account.setbank']);

    //提现
    Route::match(['get', 'post'], '/withdraw', ['uses' => 'Account\AccountController@withDraw', 'as' => 'account.withdraw']);

    });

});