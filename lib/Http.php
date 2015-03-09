<?php
class Http {
    
    function __construct() {   
    }
    
    public function sendRequest($http_method = 'GET', $url, $headers, $postData = null) {
        
        $opts = array(
            'http'=>array(
                'method'=>$http_method
            )
        );
        
        if ($http_method == 'POST' || $http_method == 'PUT')
            $opts['http']['content'] = http_build_query($postData);
        if (is_array($headers))
            $opts['http']['header'] = $this->http_build_header($headers);
        
        $context = stream_context_create($opts);
        
        return file_get_contents($url, false, $context);
    }
    
    /* - http_build_header -
        输入header数组
        返回header string:
    
        Accept-language: en\r\n
        Cookie: foo=bar\r\n
        
        Authorization: Bearer cypae4opudqi57etvv6xacnf
    */  
    private function http_build_header($headers_array) {
                
        $headers = array();
        
        foreach($headers_array as $key=>$value) {
            array_push($headers, "{$key}: {$value}");
        }
        
        return implode($headers, "\r\n");

    }
    
}