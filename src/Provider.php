<?php
require_once(__DIR__ . '/../modules/Response.php');
require_once(__DIR__ . '/../modules/PrismException.php');

class Provider {

    var $remote_arr;
    var $method;
    var $path;
    var $caller_ip;
    var $request_id;
    var $query      = array();
    var $post_data  = array();
    var $params     = array();
    var $headers    = array();
    var $app_info   = array();
    var $oauth_info = array();

    public function __construct () {

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

        // local fix
        $this->localFix();

    }

    // GET快捷方法
    public function get($path, $api, $function_name, $require_oauth = false) {
        $this->receive('GET', $path, $api, $function_name, $require_oauth);
    }

    // POST快捷方法
    public function post($path, $api, $function_name, $require_oauth = false) {
        $this->receive('POST', $path, $api, $function_name, $require_oauth);
    }

    // PUT快捷方法
    public function put($path, $api, $function_name, $require_oauth = false) {
        $this->receive('PUT', $path, $api, $function_name, $require_oauth);
    }

    // DELETE快捷方法
    public function delete($path, $api, $function_name, $require_oauth = false) {
        $this->receive('DELETE', $path, $api, $function_name, $require_oauth);
    }

    /**
    * 接受请求
    * $method: 请求方法 (GET/POST/PUT/DELETE)
    * $path: 路由/method_id
    * $api: api对象(处理器)
    * $function_name: api对象处理方法
    * $require_oauth: 是否需要oauth验证
    */
    private function receive ($method, $path, $api, $function_name, $require_oauth = false) {

        $response = new Response($this->request_id);

        // method判断
        if($method != $this->method)
            return;

        // path判断
        if($path != $this->path)
            return;

        // api对象(处理器)判断
        if( !is_object($api) )
            $response->send('error', 'Method not found', 'Handling object is not found on server');

        // 处理方法判断
        if ( !is_callable(array($api, $function_name)) )
            $response->send('error', 'Method not found', 'Handling action is not found on server');

        // oauth判断
        if ( $require_oauth && empty($oauth_info) )
            $response->send('error', 'Invalid Request', 'Oauth is required');

        call_user_func(
            array($api, $function_name),
            $this->params, $this->headers, $this->request_id, $this->oauth_info, $response
        );

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

}
