<?php

require_once(__DIR__ . '/TestBase.php');

/**
* Sign数字签名
*
* 功能
* 对Method，Path, Headers, Query, PostData, Secret进行签名
* Headers, Query, PostData需要排序
*
*/
class CurlTest extends TestBase  {

    public function testSign() {

        $method = 'GET';
        $path   = '/api/test/test';
        $headers = array(
            'X_API_UNITTEST1' => 'A',
            'X_API_UNITTEST2' => 'B'
        );
        $query = array(
            'param1' =>'C',
            'param2' =>'D',
        );
        $postData = array(
            'param1' =>'E',
            'param2' =>'F',
        );
        $secret = $this->secret;

        $r = PrismSign::produce($method, $path, $headers, $query, $postData, $secret);

        $this->assertEquals(32, strlen($r));

    }

}
