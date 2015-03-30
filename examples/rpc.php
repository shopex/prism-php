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
//$client->setRequester('curl');


// 可以携带Oauth的Access Token
$client->access_token = 'c4t6q5rh6fysu5v5ww5xenv4';


// 通过category获取AppleStore产品列表 (GET请求)
$params = array(
	'category' =>'mac',
);
$r = $client->put('/university/student', $params, $headers);
echo $r."\n";
// {"jsonrpc":"2.0","result":["macbook","macbook pro","macbook air"],"id":"4v3zjybwynqfenmx"}
