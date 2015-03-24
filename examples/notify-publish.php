<?php

require_once(__DIR__.'/../src/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');




foreach(range(1, 100) as $count) {

    $r = $client->publish('q1', 'this is some message:' . $count);

    echo "$count\n";

    usleep(10*1000);

}
