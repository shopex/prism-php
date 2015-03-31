<?php

require_once(__DIR__ . '/TestBase.php');

/**
*
* oAuth单点登录
* 功能
* getToken 获取令牌
* refreshToken 刷新令牌
* checkSession 验证令牌
*/
class OauthTest extends TestBase  {

    // 请先获取并修改成最新的有效code(token提取码/临时Token)
    // 192.168.51.50:8080/oauth/authorize?client_id=pufy2a7d
    var $code = '43sw7gs3ludec6im4nry';

    function setUp() {

        $this->assertTrue( is_string($this->local_url) );
        $this->assertTrue( is_string($this->client_id) );
        $this->assertTrue( is_string($this->secret) );

        $this->client = new PrismClient($this->local_url, $this->client_id, $this->secret);

        $this->assertTrue( is_object($this->client) );

    }

    public function testOauth() {

        // getToken
        $token = $this->client->getToken($this->code);

        $this->assertTrue( is_string($token->access_token) );
        $this->assertTrue( is_string($token->refresh_token) );

        // refreshToken
        $refreshed_token = $this->client->refreshToken($token);

        $this->assertTrue( is_string($refreshed_token->access_token) );

        // checkSession
        $check_session_result = $this->client->checkSession($refreshed_token);
        $this->assertEquals(1, $check_session_result->result);

    }

}
