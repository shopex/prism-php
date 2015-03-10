<?php
require_once('../lib/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


$result     = $client->get('/test/test');

print_r($result);
