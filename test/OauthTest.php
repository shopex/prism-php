<?php
require_once(__DIR__ . '/TestBase.php');
require_once('../Oauth.php');

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
class OauthTest extends TestBase  {

        /**
    * @depends testCURL
    *
    * oAuth单点登录
    * 功能
    * getCode 获取Token提取码(临时令牌)
    * geToken 获取令牌
    * refresh-token 刷新令牌
    */
    public function testOauth() {

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
