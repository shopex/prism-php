<?php

require_once(__DIR__ . '/../src/PrismClient.php');

class TestBase extends PHPUnit_Framework_TestCase {

    // 请先配置参数
    var $client_id  = 'pufy2a7d';
    var $secret     = 'skqovukpk2nmdrljphgj';
    var $local_url  = 'http://127.0.0.1:8080/api';
    var $remote_url = 'http://192.168.51.50:8080/api';

    var $headers = array(
        'X_API_UNITTEST1' => 'A',
        'X_API_UNITTEST2' => 'B'
    );

    var $params = array(
        'param1' =>'C',
        'param2' =>'D',
    );

}
