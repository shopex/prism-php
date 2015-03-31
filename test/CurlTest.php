<?php

require_once(__DIR__ . '/TestBase.php');

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
*/
class CurlTest extends TestBase  {

    function setUp() {

        $this->client = new PrismClient($this->local_url, $this->client_id, $this->secret);
        $this->client->access_token = 'cypae4opudqi57etvv6xacnf';
        $this->client->setRequester('curl');

    }

    public function testGET() {

        $r = $this->client->get('/test/test', $this->params, $this->headers);
        $r = json_decode($r);

        $this->assertTrue( is_object($r) );
        $this->assertEquals('GET', $r->httpMethod);
        $this->assertEquals($this->headers['X_API_UNITTEST1'], $r->header1);
        $this->assertEquals($this->headers['X_API_UNITTEST2'], $r->header2);
        $this->assertEquals(count($this->params), count((array)$r->query));
        $this->assertTrue( is_string($r->oauth) );

    }

    /**
     * @depends testGET
     */
    public function testPOST() {

        $r = $this->client->post('/test/test', $this->params, $this->headers);
        $r = json_decode($r);

        $this->assertTrue( is_object($r) );
        $this->assertEquals('POST', $r->httpMethod);
        $this->assertEquals($this->headers['X_API_UNITTEST1'], $r->header1);
        $this->assertEquals($this->headers['X_API_UNITTEST2'], $r->header2);
        $this->assertEquals(count($this->params), count((array)$r->data));
        $this->assertTrue( is_string($r->oauth) );

    }

    /**
     * @depends testPOST
     */
    public function testPUT() {

        $r = $this->client->put('/test/test', $this->params, $this->headers);
        $r = json_decode($r);

        $this->assertTrue( is_object($r) );
        $this->assertEquals('PUT', $r->httpMethod);
        $this->assertEquals($this->headers['X_API_UNITTEST1'], $r->header1);
        $this->assertEquals($this->headers['X_API_UNITTEST2'], $r->header2);
        $this->assertEquals(count($this->params), count((array)$r->data));
        $this->assertTrue( is_string($r->oauth) );

    }

    /**
     * @depends testGET
     */
    public function testDELETE() {

        $r = $this->client->delete('/test/test', $this->params, $this->headers);
        $r = json_decode($r);

        $this->assertTrue( is_object($r) );
        $this->assertEquals('DELETE', $r->httpMethod);
        $this->assertEquals($this->headers['X_API_UNITTEST1'], $r->header1);
        $this->assertEquals($this->headers['X_API_UNITTEST2'], $r->header2);
        $this->assertEquals(count($this->params), count((array)$r->query));
        $this->assertTrue( is_string($r->oauth) );

    }

}
