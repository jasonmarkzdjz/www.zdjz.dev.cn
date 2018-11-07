<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/21
 * Time: 13:54
 */


Route::group(['prefix'=>'/','middleware'=>['merchant.groupauth']],function(){

    Route::group(['prefix'=>'/merchant'],function () {
        Route::get('/', ["uses" => 'Merchant\\MerchantController@merchantLogin']);
        Route::get('/logout', ["uses" => 'Merchant\\MerchantController@merchantLogout', "as" => "merchant.logout"]);
        Route::match(['get','post'],'/pay/config', ["uses" => 'Merchant\\MerchantController@payConfig', "as" => "merchant.config.pay"]);
    });
});
//无登录态的请求
Route::get('/merchant/login', ["uses" => 'Merchant\\MerchantController@merchantLogin', "as" => "merchant.login"]);
Route::get('/merchant/register', ["uses" => 'Merchant\\MerchantController@merchantRegister', "as" => "merchant.register"]);
Route::POST('/merchant/storeklogin', ["uses" => 'Merchant\\MerchantController@merchantStoreLogin', "as" => "merchant.store.login"]);
Route::POST('/merchant/registerstore', ["uses" => 'Merchant\\MerchantController@merchantRegistStore', 'as' => 'merchant.registerstore']);