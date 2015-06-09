<?php
class Socket {
    private $socket;
    function __construct($socket=null) {
        if ($socket!=null) {
            $this->socket = $socket;
        }
    }

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
        if ($this->socket) {
            $fp = fsockopen($this->socket);
        } else {
            $fp = @fsockopen($scheme . $ip, $port, $errno, $errstr, 30);
        }

        if (!$fp)
            throw new Exception($errstr);

        if ( function_exists('stream_set_timeout') )
            stream_set_timeout($fp, 30);

        // 发起请求
        $request = $this->build_head($http_method, $path, $query, $host, $headers, $postData);

        if ($http_method == 'POST' || $http_method == 'PUT')
            $request .= http_build_query($postData);

        fwrite($fp, $request);

        // 获取结果
        $result                 = '';
        $response               = array();
        $response['Line']       = '';
        $response['Header']     = '';
        $response['Body']       = '';
        $response['StatusCode'] = '';


        $response['Line'] = fgets($fp, 128); // 解析第一行

        if(preg_match('/\d{3}/', $response['Line'], $match)) // 获取status code
            $response['StatusCode'] = $match[0];

        if($response['StatusCode'] == 101) // websocket，直接返回stream
            return $fp;

        while (!feof($fp)) {

            if ( $this->check_time_out($fp) ) // 检查有没有超时
                throw new PrismException('Socket read timeout.');

            $buffer = fgets($fp, 128);
            $result .= $buffer;
        }

        fclose($fp);

        // 解析结果
        if ($result)
            list($response['Header'], $response['Body']) = preg_split("/\r\n\r\n/", $result);

        $response['Header'] = $this->parse_http_header($response['Header']);

        if (isset($response["Header"]["Transfer-Encoding"]) && $response["Header"]["Transfer-Encoding"]=="chunked") {
            $body = $response['Body'];
            $response['Body'] = "";
            while(true){
                $p = strpos($body, "\r\n");
                $n = hexdec(substr($body, 0, $p));
                if ($n==0) break;
                $response['Body'] .= substr($body, $p+2, $n);
                $body = substr($body, $p+2 + $n+2);
            }
        }
        return $response['Body'];
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

        if ( !isset($headers['Connection']) )
            $head_arr[] = "Connection: close";

        $head_arr[] =  "\r\n";

        return implode($head_arr, "\r\n");

    }

    private function parse_http_header($str) {

        $lines = explode("\r\n", $str);
        // $head  = array(array_shift($lines)); 不需要解析第一行
        $head  = array();
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
