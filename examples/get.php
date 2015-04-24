<?php
require_once(__DIR__.'/../src/PrismClient.php');


// 运行example前先要起个服务器，比如我们用PHP的自带dev-server:
// php -S 0.0.0.0:8080 example/server-router.php


// 新建对象 填入在Prism平台上注册的信息 本地测试的话随意填写就行了
// 第四个参数 $socket socket文件地址，如果有则优先选择socke方式
$client = new PrismClient($url = 'http://default.local:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj', $socket = "unix:///tmp/api_provider.sock");


// 发起请求
$r = $client->get('/fire/get');
var_dump($r);
// 返回: pong
