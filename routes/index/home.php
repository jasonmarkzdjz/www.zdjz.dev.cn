<?php
/**
 * Created by PhpStorm.
 * User: Jason 首页路由
 * Date: 2018/5/21
 * Time: 14:44
 */
Route::group(['prefix' => '/'], function(){
    Route::get("/",["uses"=>"Index\\IndexController@show","as"=>"home.index"]);
    Route::get("/example",["uses"=>"Index\\IndexController@example","as"=>"home.example"]);
    Route::get("/special",["uses"=>"Index\\IndexController@special","as"=>"home.special"]);
    Route::get("/news",["uses"=>"Index\\IndexController@news","as"=>"home.news"]);
    Route::get("/vrshop",["uses"=>"Index\\IndexController@vrshop","as"=>"home.vrshop"]);
    Route::get("/vr3d",["uses"=>"Index\\IndexController@vr3d","as"=>"home.vr3d"]);

});