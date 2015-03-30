<?php
class Request {

    private $remote_arr;
    private $method;
    private $path;
    private $caller_ip;
    private $request_id;
    private $query      = array();
    private $post_data  = array();
    private $params     = array();
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

        // prepare headers
        foreach($_SERVER as $key=>$value) {
            if ( substr($key, 0, 7) == 'HTTP_X_')
                $this->headers[str_replace('HTTP_X_', '', $key)] = $value;
        }

        if ( isset($this->headers['API_ARG']) ) {
            parse_str($this->headers['API_ARG'], $this->app_info);
            unset($this->headers['API_ARG']);
        }

        if ( isset($this->headers['API_OAUTH']) ) {
            parse_str($this->headers['API_OAUTH'], $this->oauth_info);
            unset($this->headers['API_OAUTH']);
        }

        if ( isset($this->headers['CALLER_IP']) ) {
            $this->caller_ip = $this->headers['CALLER_IP'];
            unset($this->headers['CALLER_IP']);
        }

        if ( isset($this->headers['REQUEST_ID']) ) {
            $this->request_id = $this->headers['REQUEST_ID'];
            unset($this->headers['REQUEST_ID']);
        }

//        print_r($_SERVER);die;

        $this->localFix();

    }

    public function getParams() {
        return array_merge($this->query, $this->post_data);
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

    public function headers() {
        return $this->headers;
    }

    public function getMethod() {
        return $this->method;
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

    }

}
