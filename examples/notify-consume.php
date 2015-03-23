<?php

require_once(__DIR__.'/../src/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');





while (1) {


    $client->connectNotify();
    echo $client->consume() . "\n";
    $client->ack(1);

//    sleep(1);

}

