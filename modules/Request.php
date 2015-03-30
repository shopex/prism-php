<?php
class Request {

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

    }

}
