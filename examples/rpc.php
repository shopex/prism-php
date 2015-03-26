<?php
require_once(__DIR__.'/../src/Prism.php');

// 运行example前先要起个服务器，比如我们用PHP的自带dev-server:
// php -S 0.0.0.0:8080 test/testserver.php

// 新建对象 填入在Prism平台上注册的信息 本地测试的话随意填写就行了
$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
//$client = new Prism($url = 'http://127.0.0.1:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

// 准备一些自定义Header信息
$headers = array(
    'X_API_TEST1' => 'A',
    'X_API_TEST2' => 'B'
);

// 可以设置使用CURL还是SOCKET请求方式，默认会优先调用CURL方法
$client->setRequester('socket');
$client->setRequester('curl');


// 可以携带Oauth的Access Token
//$client->access_token = 'c4t6q5rh6fysu5v5ww5xenv4';

// 发起GET/POST/PUT/DELETE请求
$result = array();


// 通过id获取学生信息 (GET请求)
$params = array(
	'id' =>'43',
);
$r = $client->get('/university/student', $params, $headers);
echo $r."\n";
// 返回结果：{"jsonrpc":"2.0","result":{"id":43,"name":"Rose DeWitt Bukater","age":17,"desc":"Is forced into an engagement to 30-year-old Cal Hockley."},"id":"fhildwpktl7ix4f2"}


// 创建一个学生 (POST请求)
$params = array(
    'name'=>'Caledon Nathan',
    'age'=>30,
    'desc'=>"Cal is Rose's 30-year-old fiancé.",
);
$r = $client->post('/university/student', $params, $headers);
echo $r."\n";
// 返回结果：{"jsonrpc":"2.0","error":{"code":"-32600","message":"Invalid Request","data":"Oauth is required"},"id":"ejscogtwqw3gz3px"}
