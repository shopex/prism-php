<?php

require_once(__DIR__.'/../src/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');



// 往消息队列里添加100个元素
foreach(range(1, 100) as $count) {

    //第一个参数，'q1'为队列的routing_key，需要在prism端绑定相应的routing_key和访问prism的身份key
    //第二个参数为数据，仅支持字符串
    $r = $client->publish('q1', 'this is some message:' . $count);

    echo "$count\n";

    usleep(10*1000);

}
