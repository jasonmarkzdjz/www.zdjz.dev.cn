<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/5/21
 * Time: 13:54
 */


Route::group(['prefix'=>'/','middleware'=>['merchant.groupauth']],function(){

    //店铺管理
    Route::Match(['get','post'],'/shopInfo',["uses" => 'TradeMall\\ShopController@shopInfo','as'=>'trademall.shop.info']);
    Route::group(['prefix'=>'/cater'],function () {
        //产品发布
        Route::Match(['get','post'],'/publish',["uses" => 'TradeMall\\Caters\\CatersController@publishCater','as'=>'trademall.cater.publish']);

        Route::post('/panoproject',["uses" => 'TradeMall\\ShopController@getPanoByCateId','as'=>'trademall.shop.panoproject']);
        //产品列表
        Route::get('/product', ["uses" => 'TradeMall\\Caters\\CatersController@getCatersList','as'=>'trademall.cater.list']);//餐饮产品列表

        //产品上下架
        Route::post('/setpublish', ["uses" => 'TradeMall\\Caters\\CatersController@setPublish','as'=>'trademall.cater.setpublish']);

//        Route::get('/ticket', ["uses" => 'TradeMall\\Tickets\\TicketsController@getTicketsList','as'=>'merchant.trademall.ticket.list']);//票务产品列表
//
//        Route::get('/hotel', ["uses" => 'TradeMall\\Hotels\\HotelsController@getHotelsList','as'=>'merchant.trademall.hotel.list']);//票务产品列表

    });
    Route::group(['prefix'=>'/house'],function () {
        //产品发布
        Route::Match(['get','post'],'/publish',["uses" => 'TradeMall\\House\\HouseController@publish','as'=>'trademall.house.publish']);
        Route::post('/panoproject',["uses" => 'TradeMall\\ShopController@getPanoByCateId','as'=>'trademall.shop.panoproject']);
        //产品列表
        Route::get('/index', ["uses" => 'TradeMall\\House\\HouseController@Index','as'=>'trademall.house.index']);//餐饮产品列表
        //产品上下架
        Route::post('/setpublish', ["uses" => 'TradeMall\\Caters\\CatersController@setPublish','as'=>'trademall.cater.setpublish']);

    });
});



