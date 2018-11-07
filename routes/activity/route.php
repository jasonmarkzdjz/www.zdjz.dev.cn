<?php

Route::group(['prefix'=>'/activitys','middleware'=>['merchant.groupauth']],function(){

	Route::get('/',['uses'=>'Activity\IndexController@index','as'=>'activity']);

	Route::group(['prefix'=>'/'],function(){

		// 我的活动
		Route::get('/my',['uses'=>'Activity\MyActivityController@index','as'=>'act.my']);

		// 奖品管理
		Route::get('/prize',['uses'=>'Activity\PrizeController@index','as'=>'act.prize']);

		// 上传图片
		Route::post('upimg',['uses'=>'Activity\IndexController@uploadImage','as'=>'act.gui']);

		// 上传音乐
		Route::post('/upbgmusic',['uses'=>'Activity\IndexController@upMusic','as'=>'act.msc']);

		// 核销管理
		Route::get('/inspect',['uses'=>'Activity\InspectionController@index','as'=>'act.inspect']);

	});

	// 创建活动
	Route::group(['prefix'=>'/create'],function(){

		// 设置标题关键词
		Route::match(['get', 'post'],'/',['uses'=>'Activity\IndexController@create','as'=>'act.creat']);

		// 活动基础设置
		Route::match(['get', 'post'],'/config',['uses'=>'Activity\IndexController@setConfig','as'=>'act.creat.config']);

		// 奖项设置
		Route::match(['get', 'post'],'/prize',['uses'=>'Activity\IndexController@setPrize','as'=>'act.creat.prize']);

		// 中奖图片
		Route::match(['get', 'post'],'/notice',['uses'=>'Activity\IndexController@winNotice','as'=>'act.creat.notice']);

	});

	// 我的活动
	Route::group(['prefix'=>'my'],function(){

		// 发布
		Route::post('/release',['uses'=>'Activity\MyActivityController@release','as'=>'act.m.rel']);

		// 删除
		Route::post('/destroy',['uses'=>'Activity\MyActivityController@destroy','as'=>'act.m.des']);


	});

});



//活动前端页
Route::group(['prefix'=>'/act', 'middleware'=>['web']],function(){

	// 红包
	Route::match(['get', 'post'], '/redpacket', ['uses'=>'Activity\View\RedController@index', 'as'=>'act.red']);

	// 抽奖页
	Route::post('/dored', ['uses'=>'Activity\View\RedController@distribute', 'as'=>'act.dis']);


	// 验证码
	Route::post('/getcode', ['uses'=>'Activity\View\RedController@reCode', 'as'=>'act.code']);


});