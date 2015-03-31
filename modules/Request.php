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
        foreach($_SERVER as $key=>$value) {
            if ( substr($key, 0, 5) == 'HTTP_')
                $this->headers[str_replace('HTTP_', '', $key)] = $value;
        }

        if ( isset($this->headers['X_API_ARG']) ) {
            parse_str($this->headers['X_API_ARG'], $this->app_info);
//            unset($this->headers['X_API_ARG']);
        }

        if ( isset($this->headers['X_API_OAUTH']) ) {
            parse_str($this->headers['X_API_OAUTH'], $this->oauth_info);
//            unset($this->headers['X_API_OAUTH']);
        }

        if ( isset($this->headers['X_CALLER_IP']) ) {
            $this->caller_ip = $this->headers['X_CALLER_IP'];
//            unset($this->headers['X_CALLER_IP']);
        }

        if ( isset($this->headers['X_REQUEST_ID']) ) {
            $this->request_id = $this->headers['X_REQUEST_ID'];
//            unset($this->headers['X_REQUEST_ID']);
        }

//        $this->localFix();

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
            $result[$key] = $val;
        }

        return empty($result) ? false : $result;
    }

    // local fix 单元测试时本地连接本地(不经过Prism服务器)时使用
    private function localFix () {

        if ($this->remote_arr != '127.0.0.1')
            return;

        // 清理path
        $this->path = preg_replace("/^\/[^\/]+\/[^\/]+/", '', $this->path);

        // 清理params
        unset($this->params['client_id']);
        unset($this->params['sign_method']);
        unset($this->params['sign_time']);
        unset($this->params['sign']);

        // requestid
        $this->request_id = '7dukfu4ssxrfugvd';

    }

}
