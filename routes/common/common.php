<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/5
 * Time: 21:11
 */

Route::group(['prefix'=>'/'],function() {
    //地区联动
    Route::match(['get','post'],'/area/getarea',['uses'=>'Common\AreaController@getArea','as'=>'common.getarea']);
    Route::POST('/img/upload',["uses"=>'Common\\UploadController@upImage','as'=>'image.upload']);
    //获取短信验证码
    Route::POST('/sms/getsms',['uses'=>'Common\SmsController@getSms','as'=>'sms.getsms']);
    Route::POST('/apliy/rechange',['uses'=>'Common\LeanTongBaoPayController@rechange','as'=>'apliy.rechange']);
    Route::POST('/apliy/checkorder',['uses'=>'Common\LeanTongBaoPayController@checkOrder','as'=>'apliy.checkorder']);

});
