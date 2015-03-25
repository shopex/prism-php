<?php
class Provider {

    var $method;
    var $path;
    var $query = array();
    var $body = array();

    public function __construct () {

        // prepare method
        $this->method = $_SERVER['REQUEST_METHOD'];

        // prepare path and query
        $url_arr = parse_url($_SERVER['REQUEST_URI']);

        $this->path = $url_arr['path'];

        if ( isset($url_arr['query']) )
            $this->query = $this->queryToArray($url_arr['query']);

        // prepare body
        $this->body = $this->queryToArray(file_get_contents('php://input'));

    }

    public function get($path, $api, $function_name) {
        $this->receive('GET', $path, $api, $function_name);
    }

    public function post($path, $api, $function_name) {
        $this->receive('POST', $path, $api, $function_name);
    }

    public function put($path, $api, $function_name) {
        $this->receive('PUT', $path, $api, $function_name);
    }

    public function delete($path, $api, $function_name) {
        $this->receive('DELETE', $path, $api, $function_name);
    }

    private function receive ($method, $path, $api, $function_name) {

        if ($method == $this->method && $path == $this->path && is_callable(array($api, $function_name)) )
            call_user_func( array($api, $function_name), array_merge($this->query, $this->body) );

    }

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
