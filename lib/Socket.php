<?php
// todo: timeout, chunked
class Socket {

    public function sendRequest($http_method = 'GET', $url, $headers, $postData = null) {

        // 准备参数
        $url_arr  = parse_url($url);
        $host     = $url_arr['host'];
        $port     = $url_arr['port'];
        $path     = $url_arr['path'];
        $query    = $url_arr['query'];

        if($url_arr['scheme'] == 'https') {
            $scheme = 'ssl://';
            $port = 443;
        } else {
            $scheme = '';
        }

        if (preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $host))
            $ip = $host;
        else
            $ip = gethostbyname($host);


        // 新建Socket资源
        $fp = fsockopen($scheme . $ip, $port, $errno, $errstr, 30);

        if ( function_exists('stream_set_timeout') )
            stream_set_timeout($fp, 30);

        if (!$fp)
            return "{'error':'$errstr.'}";


        // 发起请求
        $request = $this->build_head($http_method, $path, $query, $host, $headers, $postData);

        if ($http_method == 'POST' || $http_method == 'PUT')
            $request .= http_build_query($postData);

        fwrite($fp, $request);

        // 获取结果
        $result = '';
        while (!feof($fp)) {
            if ( $this->check_time_out($fp) )
                return "{'error':'socket read timeout.'}";

            $result .= fgets($fp, 128);
        }

        fclose($fp);

        // 解析结果
        list($responseHead, $responseBody) = preg_split("/\r\n\r\n/", $result);

        $responseHead = $this->parse_http_header($responseHead);

        return $responseBody;
    }

    private function build_head($http_method, $path, $query, $host, $headers, $postData = null) {

        $head_arr   = array();
        $head_arr[] = "{$http_method} {$path}?{$query} HTTP/1.1";
        $head_arr[] = "Host: {$host}";
        $head_arr[] = "User-Agent: PrismSDK/PHP";

        foreach($headers as $key=>$value) {
            $head_arr[] = "$key: $value";
        }

        if ($postData)
            $head_arr[] = 'Content-Length: ' . strlen(http_build_query($postData));

        $head_arr[] = "Connection: close\r\n\r\n";

        return implode($head_arr, "\r\n");

    }

    private function parse_http_header($str) {

        $lines = explode("\r\n", $str);
        $head  = array(array_shift($lines));
        foreach ($lines as $line) {
            list($key, $val) = explode(':', $line, 2);
            if ($key == 'Set-Cookie') {
                $head['Set-Cookie'][] = trim($val);
            } else {
                $head[$key] = trim($val);
            }
        }
        return $head;

    }

    private function check_time_out($fp) {

        if ( !function_exists('stream_get_meta_data') )
            return false;

        $status = stream_get_meta_data($fp);
        return $status['timed_out'];

    }

}
