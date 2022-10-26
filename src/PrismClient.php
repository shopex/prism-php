<?php


require_once(__DIR__ . '/Requester.php');
require_once(__DIR__ . '/Oauth.php');
require_once(__DIR__ . '/Notify.php');

require_once(__DIR__ . '/../modules/PrismCurl.php');
require_once(__DIR__ . '/../modules/PrismSocket.php');
require_once(__DIR__ . '/../modules/PrismSign.php');
require_once(__DIR__ . '/../modules/PrismException.php');
require_once(__DIR__ . '/../modules/PrismClientUtil.php');

Class PrismClient extends Notify {

    public $base_url; //platform地址
    public $app_key; // key/clientID
    public $app_secret; // secret
    private $socket;
    public $is_sand_box;

    public $access_token; // access_token
    public $http; // http包

    public $client; //prism对象
    public $oauth; //oauth对象

    function __construct($base_url, $app_key, $app_secret, $socket=null) {

        $this->base_url   = rtrim($base_url, '/');
        $this->app_key    = $app_key;
        $this->app_secret = $app_secret;
        $this->socket = $socket;

        if(strstr($base_url, PrismClientUtil::SANDBOXAPIURL))
        {
            $this->is_sand_box = true;
        }
        else
        {
            $this->is_sand_box = false;
        }

        if ($socket!=null) {
            $this->http = new PrismSocket( $socket );
        } else {
            if (extension_loaded('curl'))
                $this->http = new PrismCurl();
            else
                $this->http = new PrismSocket();
        }

    }

    /**
    * GET
    */
    public function get ($path, $params = null, $headers = array(), $config = array()) {
        return $this->createRequest('GET', $path, $headers, $params, $config);
    }

    /**
    * POST
    */
    public function post ($path, $params = null, $headers = array(), $config = array()) {
        return $this->createRequest('POST', $path, $headers, $params, $config);
    }

    /**
    * PUT
    */
    public function put ($path, $params = null, $headers = array(), $config = array()) {
        return $this->createRequest('PUT', $path, $headers, $params, $config);
    }

    /**
    * DELETE
    */
    public function delete ($path, $params = null, $headers = array(), $config = array()) {
        return $this->createRequest('DELETE', $path, $headers, $params, $config);
    }

}
