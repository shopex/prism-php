<?php
class Requester {

    /**
    * 发起请求
    */
    protected function createRequest ($http_method, $path, $headers = array(), $params = null) {

        // 获取完整URL信息
        $url = $this->base_url .'/'. ltrim($path, '/');

        if (substr($path, 0, 6) == '/oauth') // oauth url fix
            $url = str_replace('/api', '', $url);

        $url_arr = parse_url($url);


        // 准备query, headers, postData
        $query    = array();
        $postData = array();

        $headers['Pragma']        = 'no-cache';
        $headers['Cache-Control'] = 'no-cache';

        switch ($http_method) {

            case 'GET':
            case 'DELETE':
                if ($params)
                    $query = array_merge($query, $params);
            break;

            case 'POST':
            case 'PUT':
                if ($params)
                    $postData = array_merge($postData, $params);

                $headers['Content-Type']  = 'application/x-www-form-urlencoded';
            break;

        }

        $query['client_id']   = $this->app_key;
        $query['sign_method'] = 'md5';
        $query['sign_time']   = time();

        if($this->access_token)
            $headers["Authorization"] = "Bearer " . $this->access_token;

        // 生成数字签名
        $query['sign'] = PrismSign::produce($http_method, $url_arr['path'], $headers, $query, $postData, $this->app_secret);

        // https
        if ($url_arr['scheme'] == 'https') {
            $query                  = array();
            $query['client_id']     = $this->app_key;
            $query['client_secret'] = $this->app_secret;
        }

        // 拼装最后Url
        $final_url = preg_replace("/\?.*/", '', $url) . '?' . http_build_query($query);

        // 发起请求 CURL/SOCKET
        return $this->http->sendRequest($http_method, $final_url, $headers, $postData);

    }

    /**
    * 设置HTTP包的类型
    */
    public function setRequester($string) {
        if ($string == 'curl')
            $this->http = new Curl();

        if ($string == 'socket')
            $this->http = new Socket();
    }

    /**
    * 备用http方法
    */
    public function fileGetContents($http_method = 'GET', $url, $headers, $postData = null) {

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

    /**
    * 输入header数组
    * 返回:
    * Accept-language: en\r\n
    * Cookie: foo=bar\r\n
    * Authorization: Bearer cypae4opudqi57etvv6xacnf\r\n\r\n
    */
    private function http_build_header($headers_array) {

        $headers = array();

        foreach($headers_array as $key=>$value) {
            array_push($headers, "{$key}: {$value}");
        }

        return implode($headers, "\r\n");

    }

}
