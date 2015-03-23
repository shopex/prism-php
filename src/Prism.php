<?php
require_once(__DIR__ . '/Requester.php');
require_once(__DIR__ . '/Oauth.php');
require_once(__DIR__ . '/Notify.php');

require_once(__DIR__ . '/../modules/Curl.php');
require_once(__DIR__ . '/../modules/Socket.php');
require_once(__DIR__ . '/../modules/Sign.php');
require_once(__DIR__ . '/../modules/PrismException.php');

Class Prism extends Notify {

    public $base_url; //platform地址
    public $app_key; // key
    public $app_secret; // secret

    public $access_token; // access_token
    public $http; // http包

    public $client; //prism对象
    public $oauth; //oauth对象

    function __construct($base_url, $app_key, $app_secret) {

        $this->base_url   = rtrim($base_url, '/');
        $this->app_key    = $app_key;
        $this->app_secret = $app_secret;

        if (extension_loaded('curl'))
            $this->http = new Curl();
        else
            $this->http = new Socket();

    }

    /**
    * GET
    */
    public function get ($path, $params = null, $headers = null) {
        return $this->createRequest('GET', $path, $headers, $params);
    }

    /**
    * POST
    */
    public function post ($path, $params = null, $headers = null) {
        return $this->createRequest('POST', $path, $headers, $params);
    }

    /**
    * PUT
    */
    public function put ($path, $params = null, $headers = null) {
        return $this->createRequest('PUT', $path, $headers, $params);
    }

    /**
    * DELETE
    */
    public function delete ($path, $params = null, $headers = null) {
        return $this->createRequest('DELETE', $path, $headers, $params);
    }

}
