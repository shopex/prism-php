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
    path中的query会被合并到Query或者Body里
*/
class CurlTest extends TestBase  {

    function __construct () {
        $this->client = new Prism($this->url, $this->client_id, $this->secret);
        $this->client->access_token = 'cypae4opudqi57etvv6xacnf';
        $this->client->requester = 'curl';
    }

    public function testGET() {

        $httpGetResult = 'httpGetResult:ERROR';
        $r = $this->client->get('/test/test?param3=E&param4=F', $this->params, $this->headers);
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

    }

    public function testPOST() {

        $httpPostResult = 'httpPostResult:ERROR';
        $r = $this->client->post('/test/test?param3=E&param4=F', $this->params, $this->headers);
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

    }

    public function testPUT() {

        $httpPutResult = 'httpPutResult:ERROR';
        $r = $this->client->put('/test/test?param3=E&param4=F', $this->params, $this->headers);
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

    }

    public function testDELETE() {

        $httpDeleteResult = 'httpDeleteResult:ERROR';
        $r = $this->client->delete('/test/test?param3=E&param4=F', $this->params, $this->headers);
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

    }

}
