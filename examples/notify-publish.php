<?php

require_once(__DIR__.'/../src/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

$client->connectNotify();


$arr          = range('a', 'z');
$message      = $arr[mt_rand(0,25)];
$routing_key  = 'q1';

//while (1) {

    $message      = $arr[mt_rand(0,25)];
    $r = $client->publish($routing_key, $message);

    if ($r)
        echo "added $message to $routing_key\n";

    sleep(1);

//}



