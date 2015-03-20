<?php

require_once(__DIR__.'/../src/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


$client->connect();

echo $client->consume() . "\n";
$client->ack(1);
//echo $client->publish('q1', 'hello');
