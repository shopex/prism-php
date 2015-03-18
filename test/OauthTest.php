<?php
require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../src/Prism.php');

/**
*
* oAuth单点登录
* 功能
* getToken 获取令牌
* refresh-token 刷新令牌
* checkSession 验证令牌
*/
class OauthTest extends TestBase  {

    var $client = '';
    var $token  = '';

    function __construct () {
        $this->client = new Prism($this->url, $this->client_id, $this->secret);
    }

    public function testOauth() {

        $token = $this->client->getToken($this->code);
        $token = $this->client->refreshToken($token);

        if ($token->access_token)
            $result = $this->client->checkSession($token);

        $this->assertEquals(1, $result->result);
    }

}
