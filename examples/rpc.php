<?php
require_once(__DIR__.'/../src/Prism.php');

// 运行example前先要起个服务器，比如我们用PHP的自带dev-server:
// php -S 0.0.0.0:8080 test/testserver.php

// 新建对象 填入在Prism平台上注册的信息 本地测试的话随意填写就行了
$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
//$client = new Prism($url = 'http://127.0.0.1:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

// 准备一些自定义Header信息
$headers = array(
    'X_API_UNITTEST1' => 'A',
    'X_API_UNITTEST2' => 'B'
);

// 准备一些需要传递给API的参数
$params = array(
	'student id' =>'101',
	'student name' =>'Jason',
);

// 可以设置使用CURL还是SOCKET请求方式，默认会优先调用CURL方法
$client->setRequester('socket');
$client->setRequester('curl');

// 发起GET/POST/PUT/DELETE请求
$result = array();

$result['GET']      = $client->get('/university/student', $params, $headers);
$result['POST']     = $client->post('/university/student', $params, $headers);
$result['PUT']      = $client->put('/university/student', $params, $headers);
$result['DELETE']   = $client->delete('/university/student', $params, $headers);

print_r($result);

/*
返回：
Array
(
    [GET] => {"httpMethod":"GET","header1":"A","header2":"B","query":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

    [POST] => {"httpMethod":"POST","header1":"A","header2":"B","data":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

    [PUT] => {"httpMethod":"PUT","header1":"A","header2":"B","data":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

    [DELETE] => {"httpMethod":"DELETE","header1":"A","header2":"B","query":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

)
*/
