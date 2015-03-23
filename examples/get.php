<?php
require_once(__DIR__.'/../src/Prism.php');


// 运行example前先要起个服务器，比如我们用PHP的自带dev-server:
// php -S 0.0.0.0:8080 test/testserver.php

// 新建对象 填入在Prism平台上注册的信息 本地测试的话随意填写就行了
//$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
$client = new Prism($url = 'http://127.0.0.1:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


// 设置底层请求方式（默认用curl）
//$client->setRequester('socket');

// 发起请求
$client->get('/test/test');

// 应该返回
// {"httpMethod":"GET","responseTime":"10ms"}
