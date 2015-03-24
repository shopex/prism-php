<?php

require_once(__DIR__.'/../src/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');



while(1) {

    $r = $client->consume();
    $r = json_decode($r);

    echo "$r->body\n";
    $client->ack($r->tag);

}
