<?php
// 给PHP-SDK Provider用来获取PHP Global参数并封装成请求对象
interface RequestInterface {

    // 获取请求中的Params
    public function getParams();

    // 获取请求中的Request ID
    public function getRequestID();

    // 获取用户登录的信息
    public function getOauth();

    // 获取应用的信息
    public function getAppInfo();

    //获取请求者IP
    public function getCallerIP();

    // 获取自定义头信息
    public function getHeaders();

    // 获取请求的类型GET/POST/PUT/DELETE
    public function getMethod();

    // 获取请求的地址 (path)
    public function getPath();

}

class Request implements RequestInterface {

    private $remote_arr;
    private $method;
    public $path;
    private $caller_ip;
    private $request_id;
    private $query      = array();
    private $post_data  = array();
    public $params     = array();
    private $headers    = array();
    private $app_info   = array();
    private $oauth_info = array();

    function __construct() {

        // prepare remote address
        $this->remote_arr = $_SERVER['REMOTE_ADDR'];

        // prepare method
        $this->method = $_SERVER['REQUEST_METHOD'];

        // prepare path and query
        $url_arr = parse_url($_SERVER['REQUEST_URI']);

        $this->path = $url_arr['path'];

        if ( isset($url_arr['query']) )
            $this->query = $this->queryToArray($url_arr['query']);

        // prepare post_data
        $this->post_data = $this->queryToArray(file_get_contents('php://input'));

        // prepare params
        $this->params = array_merge($this->query, $this->post_data);

        // prepare headers
        $this->headers = $this->parseRequestHeaders();

        if ( isset($this->headers['X-Api-Arg']) )
            parse_str($this->headers['X-Api-Arg'], $this->app_info);

        if ( isset($this->headers['X-Api-Oauth']) )
            parse_str($this->headers['X-Api-Oauth'], $this->oauth_info);

        if ( isset($this->headers['X-Caller-Ip']) )
            $this->caller_ip = $this->headers['X-Caller-Ip'];

        if ( isset($this->headers['X-Request-Id']) )
            $this->request_id = $this->headers['X-Request-Id'];

    }

    public function getParams() {
        return $this->params;
    }

    public function getRequestID() {
        return $this->request_id;
    }

    public function getOauth() {
        return $this->oauth_info;
    }

    public function getAppInfo() {
        return $this->app_info;
    }

    public function getCallerIP() {
        return $this->caller_ip;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPath() {
        return $this->path;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getPostData() {
        return $this->post_data;
    }

    // query to array
    private function queryToArray($query) {

        if(empty($query))
            return array();

        $result = array();
        //string must contain at least one = and cannot be in first position
        if(strpos($query,'=')) {
            if(strpos($query,'?')!==false) {
                $q = parse_url($query);
                $query = $q['query'];
            }
        } else {
            return false;
        }

        foreach (explode('&', $query) as $couple) {
            list ($key, $val) = explode('=', $couple);
            $key = str_replace("+", " ", $key); // key里的+转换成空格
            $result[$key] = urldecode($val); // 增加urldecode
        }

        return empty($result) ? false : $result;
    }

    // 修复http headers
    function parseRequestHeaders() {

        $headers = array();

        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        return $headers;

    }

}
