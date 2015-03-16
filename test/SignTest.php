<?php

require_once(__DIR__ . '/TestBase.php');
require_once(__DIR__ . '/../lib/Sign.php');

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

}
