<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/13
 * Time: 15:58
 */
namespace library\Client;
interface Contract{

    /**
     * @param array $data
     * @return object
     */
    public  function get($url,$headers = []);

    /**
     * @param array $data
     * @return object
     */
    public  function post($url,$params = []);

    /**
     * @param array $data
     * @return object
     */
    public  function put($url);

    /**
     * @param 异步
     * @return object
     */
    public  function getAsync($url);
    /**
     * @param 异步
     * @return object
     */
    public  function postAsync($url);
    /**
     * @param 异步
     * @return object
     */
    public  function putAsync($url);
}