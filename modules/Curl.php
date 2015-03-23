<?php
class Curl {

    public function sendRequest($http_method = 'GET', $url, $headers, $postData = null) {

        // 初始化一个cURL对象
        $curl = curl_init();

        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // 设置是否返回header
        curl_setopt($curl, CURLOPT_HEADER, 0);

        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 设置超时时间
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        // 设置HTTP METHOD
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $http_method);

        // 设置UserAgent
        curl_setopt($curl, CURLOPT_USERAGENT, 'PrismSDK/PHP');

        // 添加Header
        $header_arr = array();
        foreach($headers as $key=>$value) {
            array_push($header_arr, "$key: $value");
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_arr);

        // 添加postData
        if ($http_method == 'POST' || $http_method == 'PUT') {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        // 运行cURL，发起请求
        $data = curl_exec($curl);

        // 遇到错误
        if ( curl_errno($curl) )
            throw new PrismException( curl_error($curl) );

        // 关闭URL请求
        curl_close($curl);

        // 返回结果
        return $data;
    }

}
