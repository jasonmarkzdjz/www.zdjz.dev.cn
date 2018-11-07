<?php

// pano 应用路由入口
Route::group(['prefix'=>'/', 'middleware'=>['merchant.groupauth']], function(){
// Route::group(['prefix'=>'/', 'middleware'=>[]], function(){

    Route::group(['prefix'=>'/pano'],function () {

        /**
         * 全景项目
         */
        Route::match(['get','post'],'', ['uses'=>'Pano\PanoController@index', "as"=>"pano.index"]);

        Route::match(['get','post'],'index', ['uses'=>'Pano\PanoController@index', "as"=>"pano.index"]);

        Route::match(['get','post'],'addpost', ['uses'=>'Pano\PanoController@addPost', "as"=>"pano.addpost"]);

        Route::match(['get','post'],'delete', ['uses'=>'Pano\PanoController@delete', "as"=>"pano.delete"]);

        Route::match(['get','post'],'move', ['uses'=>'Pano\PanoController@move', "as"=>"pano.move"]);

        Route::match(['get','post'],'edit', ['uses'=>'Pano\PanoController@edit', "as"=>"pano.edit"]);

        Route::match(['get','post'],'editpost', ['uses'=>'Pano\PanoController@editPost', "as"=>"pano.editpost"]);

        Route::match(['get','post'],'view', ['uses'=>'Pano\PanoController@view',"as"=>"pano.view"]);

        Route::match(['get','post'],'xml', ['uses'=>'Pano\PanoController@xml',"as"=>"pano.xml"]);

        Route::match(['get','post'],'audio', ['uses'=>'Pano\PanoController@audio',"as"=>"pano.audio"]);

        Route::match(['get','post'], 'speech/previewmp3', ['uses'=>'Pano\SpeechController@previewMp3',"as"=>"speech.previewmp3"]);

        // 全景分类
        Route::match(['get','post'],'cate/savepost', ['uses'=>'Pano\PanoCateController@savePost', "as"=>"panocate.savepost"]);

        Route::match(['get','post'],'group/delete', ['uses'=>'Pano\PanoCateController@delete', "as"=>"panocate.delete"]);

        // 全景分组
        Route::match(['get','post'],'group/edit/{id}', ['uses'=>'Pano\PanoGroupController@edit', "as"=>"panogroup.edit"]);

        Route::match(['get','post'],'group/list', ['uses'=>'Pano\PanoGroupController@list', "as"=>"panogroup.list"]);

        Route::match(['get','post'],'group/addpost', ['uses'=>'Pano\PanoGroupController@addPost', "as"=>"panogroup.addpost"]);

        Route::match(['get','post'],'group/editpost', ['uses'=>'Pano\PanoGroupController@editPost', "as"=>"panogroup.editpost"]);

        Route::match(['get','post'],'group/delete/{id}', ['uses'=>'Pano\PanoGroupController@cancel', "as"=>"panogroup.cancel"]);

        // AJAX 全景标签列表
        Route::match(['get','post'],'tag/list', ['uses'=>'Pano\PanoTagController@list', "as"=>"panotag.list"]);

        // AJAX 商户商品列表查询
        Route::match(['get','post'],'goods/list', ['uses'=>'Pano\PanoGoodsController@list', "as"=>"panogoods.list"]);

        // AJAX全景图上传
        Route::match(['get','post'],'ajaxupload', ['uses'=>'Pano\PanoUploadController@ajaxUpload', "as"=>"pano.ajaxupload"]);

        // AJAX用户自定义标注点上传
        Route::match(['get','post'],'point/ajaxupload', ['uses'=>'Pano\PluginPointController@ajaxUpload', "as"=>"point.ajaxupload"]);
        // AJAX用户自定义标注点添加
        Route::match(['get','post'],'point/addpost', ['uses'=>'Pano\PluginPointController@addPost', "as"=>"point.addpost"]);
        // AJAX用户自定义标注点修改
        Route::match(['get','post'],'point/editpost', ['uses'=>'Pano\PluginPointController@editPost', "as"=>"point.editpost"]);
        // AJAX用户自定义标注点删除
        Route::match(['get','post'],'point/delete', ['uses'=>'Pano\PluginPointController@delete', "as"=>"point.delete"]);
        // AJAX用户定义的标注点列表
        Route::match(['get','post'],'point/imglist', ['uses'=>'Pano\PluginPointController@imgList', "as"=>"point.imglist"]);

        // AJAX用户自定义漫游点上传
        Route::match(['get','post'],'roam/ajaxupload', ['uses'=>'Pano\PluginRoamController@ajaxupload', "as"=>"roam.ajaxupload"]);
        // AJAX用户自定义漫游点添加
        Route::match(['get','post'],'roam/addpost', ['uses'=>'Pano\PluginRoamController@addPost', "as"=>"roam.addpost"]);
        // AJAX用户自定义漫游点修改
        Route::match(['get','post'],'roam/editpost', ['uses'=>'Pano\PluginRoamController@editPost', "as"=>"roam.editpost"]);
        // AJAX用户自定义漫游点删除
        Route::match(['get','post'],'roam/delete', ['uses'=>'Pano\PluginRoamController@delete', "as"=>"roam.delete"]);
        // AJAX用户定义的漫游点列表
        Route::match(['get','post'],'roam/imglist', ['uses'=>'Pano\PluginRoamController@imgList', "as"=>"roam.imglist"]);

        // AJAX 加载样式上传
        Route::match(['get','post'],'loading/ajaxupload', ['uses'=>'Pano\PluginLoadingController@ajaxUpload', "as"=>"loading.ajaxupload"]);
        
        // AJAX 开场动画上传
        Route::match(['get','post'],'worldofwar/ajaxupload', ['uses'=>'Pano\PluginWorldofwarController@ajaxUpload', "as"=>"worldofwar.ajaxupload"]);

        /**
         * 素材中心
         */
        Route::match(['get','post'],'material/index', ['uses'=>'Pano\MaterialController@index', "as"=>"material.index"]);

        Route::match(['get','post'],'material/edit', ['uses'=>'Pano\MaterialController@edit', "as"=>"material.edit"]);

        Route::match(['get','post'],'material/editpost', ['uses'=>'Pano\MaterialController@editPost', "as"=>"material.editpost"]);
        
        /**
         * 脚本任务
         */
        Route::match(['get','post'],'scene/list', ['uses'=>'Pano\TaskSceneController@list', "as"=>"taskscene.list"]);

        Route::match(['get','post'],'scene/batchmove', ['uses'=>'Pano\TaskSceneController@batchMove', "as"=>"scene.batchmove"]);
    });
    
});