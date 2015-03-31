<?php
require_once(__DIR__.'/../src/PrismClient.php');


// 运行example前先要起个服务器，比如我们用PHP的自带dev-server:
// php -S 0.0.0.0:8080 example/server-router.php

// 新建对象 填入在Prism平台上注册的信息 本地测试的话随意填写就行了
$client = new PrismClient($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
//$client = new PrismClient($url = 'http://127.0.0.1:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


// 发起请求
echo $client->get('/apple_store/ping');

// 返回: pong
