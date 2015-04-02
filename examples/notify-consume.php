<?php

require_once(__DIR__.'/../src/PrismClient.php');

$client = new PrismClient($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


// 往消息队列里获取(消费)元素并确认
while(1) {

    //访问队列，获取数据
    $r = $client->consume();

    //这里对获取到的数据进行处理
    $r = json_decode($r);

    echo "$r->body\n";

    //处理完成后执行ack，表示处理成功，队列中的数据会删除
    $client->ack($r->tag);

}
