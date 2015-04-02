<?php
class PrismSign {

    //Method        = GET | POST | DELETE | PUT ...
    //Path          = /path/to/method
    //headers       = urnencode(HeaderKey1 + HeaderValue1 + HeaderKey1 + HeaderValue1 ...)
    //GetParams     = urnencode(GetKey1 + GetValue1 + GetKey2 + GetValue2 ...)
    //PostParams    = urnencode(PostKey1 + PostValue1 + PostKey2 + PostValue2 ...)
    //ClientSecret  = key的Secret密钥
    public static function produce ($method, $path, $headers, $query, $postData, $secret) {

        $sign = array(
            $secret,
            $method,
            rawurlencode($path),
            rawurlencode(self::sign_headers($headers)),
            rawurlencode(self::sign_params($query)),
            rawurlencode(self::sign_params($postData)),
            $secret
        );

        $sign = implode('&', $sign);

        return strtoupper(md5($sign));
    }

    // 对Header进行排序 只留下Authorization和X-Api-开始的Header
    private static function sign_headers($headers) {
        if(is_array($headers)){
            ksort($headers);
            $result = array();
            foreach($headers as $key=>$value){
                if ( ($key == 'Authorization') || (substr($key, 0, 6)=='X-Api-') ) {
                    $result[] = $key.'='.$value;
                }
            }
            return implode('&', $result);
        }
    }

    // 对参数进行排序
    private static function sign_params($params) {
        if(is_array($params)) {
            ksort($params);
            $result = array();
            foreach($params as $key=>$value){
                if ($value === false)
                    $value = 0;
                if ($value !== null)
                    $result[] = $key.'='.$value;
            }
            return implode('&', $result);
        }
    }
}


