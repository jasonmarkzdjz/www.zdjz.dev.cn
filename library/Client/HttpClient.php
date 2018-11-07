<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 2018/6/13
 * Time: 15:54
 */
namespace library\Client;

use GuzzleHttp\Client;

class HttpClient implements Contract {

    protected static $base_url;
    /**
     * 客户端对象
     * @var guzzle
     */
    protected  $Guzzle;

    protected static $instance;

    public function __construct() {
        self::$base_url = env('API_VRBASE_URL');
        $this->Guzzle = new Client(['base_uri'=>self::$base_url,'http_errors'=>false]);
    }

    public static function getInstance($options = array()) {
        if(self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * get请求
     * @param string $base_uri   设置uri
     * @param string $api 请求api
     * @param array $headers 请求头
     * @return mixed
     * @throws \Exception
     */
    public  function get($url,$headers = []) {
        return $this->Guzzle->get($url,$headers);
    }

    public  function post($url,$params = []) {
        // TODO: Implement post() method.
        return $this->Guzzle->post($url,$params);
    }

    public  function put($url) {
        // TODO: Implement put() method.
        return $this->Guzzle->post($url);
    }

    public function getAsync($url) {
        // TODO: Implement getAsync() method.
    }

    public function postAsync($url) {
        // TODO: Implement postAsync() method.
    }
    public function putAsync($url) {
        // TODO: Implement putAsync() method.
    }
}