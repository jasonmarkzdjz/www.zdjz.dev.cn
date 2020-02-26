<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2019/12/20
 * Time: 14:52
 */

Route::get('/rabbitmq/exec', ["uses" => 'Rabbitmq\\Formant\\FormantController@exec', "as" => "rabbitmq.formant.sexec"]);
Route::get('/rabbitmq/direct/exec', ["uses" => 'Rabbitmq\\Formant\\DirectController@exec', "as" => "rabbitmq.direct.exec"]);
Route::get('/rabbitmq/topic/exec', ["uses" => 'Rabbitmq\\Formant\\TopicController@exec', "as" => "rabbitmq.topic.exec"]);
Route::get('/rabbitmq/dead/exec', ["uses" => 'Rabbitmq\\Formant\\DeadTtlExchangeController@exec', "as" => "rabbitmq.dead.exec"]);
Route::get('/rabbitmq/back/exec', ["uses" => 'Rabbitmq\\Formant\\BackupExchange@exec', "as" => "rabbitmq.back.exec"]);
