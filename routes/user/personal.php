<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/5
 * Time: 22:10
 */

Route::group(['prefix'=>'/','middleware'=>['merchant.groupauth']],function() {

    Route::group(['prefix'=>'/personal'],function () {

        Route::get('/', ["uses" => 'Merchant\\MerchantController@ucenter', 'as' => 'merchant.ucenter']);

    //编辑资料
        Route::match(['get', 'post'], '/info', ['uses' => 'Merchant\MerchantController@editInformation', 'as' => 'merchant.info']);
    //个人认证
        Route::match(['get', 'post'], '/personal', ['uses' => 'Merchant\MerchantController@personalAuth', 'as' => 'merchant.personal']);
    //企业认证
        Route::match(['get', 'post'], '/enterprise', ['uses' => 'Merchant\MerchantController@enterpriseAuth', 'as' => 'merchant.enterprise']);
    //密码管理
        Route::match(['get', 'post'], '/change', ['uses' => 'Merchant\MerchantController@changePwd', 'as' => 'merchant.change']);
    //获取验证码
        Route::post('/verCode', ['uses' => 'Merchant\MerchantController@verifyCode', 'as' => 'merchant.verCode']);
    //上传认证图片
        Route::post('/authup', ['uses' => 'Merchant\MerchantController@upAuth', 'as' => 'merchant.authup']);

    //修改手机号
        Route::post('/mobile', ['uses' => 'Merchant\MerchantController@phoneChange', 'as' => 'merchant.mobile']);

    //忘记密码
        Route::get('/forgetPassword', ["uses" => 'Merchant\\MerchantController@forgetPwd', 'as' => 'merchant.forgetPassword',]);

        Route::POST('/newPwd', ["uses" => 'Merchant\\MerchantController@newPwd', 'as' => 'merchant.newPwd']);
    });
});