<?php

require_once(__DIR__.'/../src/PrismClient.php');

$client = new PrismClient($url = 'http://default.local:8080/api', $key = 'biwwtdql', $secret = 'v7y3o4rbngjasoa7bvgd');


// 往消息队列里获取(消费)元素并确认
while(1) {

    //访问队列，获取数据
    $r = $client->consume("test");

    //这里对获取到的数据进行处理
    $r = json_decode($r);

    echo "$r->body\n";

    //处理完成后执行ack，表示处理成功，队列中的数据会删除
    $client->ack($r->tag);

}
