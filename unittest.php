<?php
require_once('lib/Prism.php');
require_once('lib/Oauth.php');
require_once('lib/Sign.php');

/*

HTTP请求跳转
HTTP请求代理
HTTP KEEPLIVE
HTTP请求SSL(HTTPS)
HTTP请求chunk模式(HTTP1.1)
DNS解析

*/
class test extends PHPUnit_Framework_TestCase {
    
    var $client_id  = 'pufy2a7d';
    var $secret     = 'skqovukpk2nmdrljphgj';
    var $url        = 'http://192.168.51.50:8080/api';
    var $oauth_url  = '192.168.51.50:8080/oauth/authorize?client_id=pufy2a7d';
    var $code       = 'y4shnnkzycxmfcq7rk63';
    
    var $http_client;
    var $https_client;
    var $oauth_client;
    
    // 建立连接
    function __construct() {
        
        // openssl true
        // var_dump(extension_loaded('openssl'));
        
        $this->http_client = new Prism($this->url, $this->client_id, $this->secret);
        $this->http_client->access_token = 'cypae4opudqi57etvv6xacnf';
        
        $url = str_replace('http', 'https', $this->url);
        $this->https_client = new Prism($url, $this->client_id, $this->secret);
        $this->https_client->access_token = 'cypae4opudqi57etvv6xacnf';
        
        $url = str_replace('/api', '', $this->url);
        $this->oauth_client = new Prism($url, $this->client_id, $this->secret);
    }
    
    /*
        CURL请求测试
        
        用途：
        测试SDK的基础HTTP(CURL)请求功能
        
        功能：
        发起HTTP请求
        GET方法参数通过Query传递
        POST方法参数通过Body传递
        PUT方法参数通过Body传递
        DELETE方法参数通过Query传递
        能够携带自定义Header
        path中的query会被合并到Query或者Body里
    */
    public function testCURL() {
        
        $headers = array(
            'X_API_UNITTEST1' => 'A',
            'X_API_UNITTEST2' => 'B'
        );

        $params = array(
            'param1' =>'C',
            'param2' =>'D',
        );        
        
        // HTTP GET
        $httpGetResult = 'httpGetResult:ERROR';
        $r = $this->http_client->get('/test/test?param3=E&param4=F', $params, $headers);
        $r = json_decode($r);
        
        if (
            $r->httpMethod == 'GET' && 
            $r->header1 == 'A' &&
            $r->header2 == 'B' &&
            $r->oauth &&
            count((array)$r->query) == 4
        )
            $httpGetResult = 'httpGetResult:OK';
       
        $this->assertEquals('httpGetResult:OK', $httpGetResult);
        
        // HTTP POST
        $httpPostResult = 'httpPostResult:ERROR';
        $r = $this->http_client->post('/test/test?param3=E&param4=F', $params, $headers);
        $r = json_decode($r);
        
        if (
            $r->httpMethod == 'POST' && 
            $r->header1 == 'A' &&
            $r->header2 == 'B' &&
            $r->oauth &&
            count((array)$r->data) == 4
        )
            $httpPostResult = 'httpPostResult:OK';

        $this->assertEquals('httpPostResult:OK', $httpPostResult);   
                
        // HTTP PUT
        $httpPutResult = 'httpPutResult:ERROR';
        $r = $this->http_client->put('/test/test?param3=E&param4=F', $params, $headers);
        $r = json_decode($r);
        
        if (
            $r->httpMethod == 'PUT' &&
            $r->header1 == 'A' &&
            $r->header2 == 'B' &&
            $r->oauth &&
            count((array)$r->data) == 4
        )
            $httpPutResult = 'httpPutResult:OK';
        $this->assertEquals('httpPutResult:OK', $httpPutResult); 
                        
        // HTTP DELETE
        $httpDeleteResult = 'httpDeleteResult:ERROR';
        $r = $this->http_client->delete('/test/test?param3=E&param4=F', $params, $headers);
        $r = json_decode($r);
        
        if (
            $r->httpMethod == 'DELETE' &&
            $r->header1 == 'A' &&
            $r->header2 == 'B' &&
            $r->oauth &&
            count((array)$r->query) == 4
        )
            $httpDeleteResult = 'httpDeleteResult:OK';
        $this->assertEquals('httpDeleteResult:OK', $httpDeleteResult); 

        

        
        // HTTPS GET
//        $httpGetResult = 'httpGetResult:ERROR';
//        $r = $this->https_client->get('/test/test?param3=E&param4=F', $params, $headers);
//        $r = json_decode($r);
//        
//        if (
//            $r->httpMethod == 'GET' && 
//            $r->header1 == 'A' &&
//            $r->header2 == 'B' &&
//            $r->oauth &&
//            count((array)$r->query) == 4
//        )
//            $httpGetResult = 'httpGetResult:OK';
//        print_r($r);
//        $this->assertEquals('httpGetResult:OK', $httpGetResult);
    }
    
    
    /**
    * Sign数字签名
    *
    * 功能    
    * 对Method，Path, Headers, Query, PostData, Secret进行签名
    * Headers, Query, PostData需要排序
    * 
    */    
    public function testSign() {
        $headers = array(
            'X_API_UNITTEST1' => 'A',
            'X_API_UNITTEST2' => 'B'
        );

        $params1 = array(
            'param1' =>'C',
            'param2' =>'D',
        );    
        
        $params2 = array(
            'param1' =>'E',
            'param2' =>'F',
        );     
        
        $signResult = 'signResult:ERROR';
        $r = Sign::produce('GET', '/api/test/test', $headers, $params1, $params2, $this->secret);
        if ($r)
            $signResult = 'signResult:OK';
        $this->assertEquals('signResult:OK', $signResult); 
    }
    
    /**
    * @depends testCURL
    *
    * oAuth单点登录
    * 功能    
    * getCode 获取Token提取码(临时令牌)
    * geToken 获取令牌
    * refresh-token 刷新令牌
    */
    public function NtestOauth() {
        
        // 模拟用户资料
        $token = '
        {                                                     
          "access_token": "hisnevh73w5p2taw2tmsloxy",         
          "data": {                                           
            "@id": "test",                                    
            "id": "1",                                        
            "name": "test",                                   
            "passwd": "test"                                  
          },                                                  
          "expires_in": 1425545562,                           
          "refresh_expires": 1428133962,                      
          "refresh_token": "z54umfgfr4xaphfqdym65agroatym53q",
          "session_id": "4ebmbpw27cdyuwchmoevem"              
        }                                                
        ';
        $token = json_decode($token);
//        $token = '';
        // end
        
        
        // 开始测试
        $oauth = new Oauth($this->oauth_client);
        
        // getToken
        $getTokenResult = 'getTokenResult:ERROR';
        $r =  $oauth->getToken($this->code);
        if ($r->access_token) {
            $token = $r;
            $getTokenResult = 'getTokenResult:OK';
        }
//        print_r($token);
//        $this->assertEquals('getTokenResult:OK', $getTokenResult);
        
        
        // refreshToken
        $refreshTokenResult = 'refreshTokenResult:ERROR';
        $r =  $oauth->refreshToken($token);
        if ($r->access_token) {
            $token = $r;
            $refreshTokenResult = 'refreshTokenResult:OK';
        }   
//        print_r($token);
        $this->assertEquals('refreshTokenResult:OK', $refreshTokenResult);
        
        
        // checkSession
        $checkSessionResult = 'checkSessionResult:ERROR';
        $r =  $oauth->checkSession($token);
        if (is_object($r))
            if ($r->result || $r->error == 'not found')
                $checkSessionResult = 'checkSessionResult:OK';
        $this->assertEquals('checkSessionResult:OK', $checkSessionResult);
        
        

    }
}

