<?php
require_once(__DIR__ . '/../src/PrismClient.php');

$shopeopen_url = 'http://openapi.shopex.cn/api-sandbox';
$key = 'cqmr24c2';
$secret = 'pywi56ec3ugv6vcvw3gx';

$client = new PrismClient($shopeopen_url, $key, $secret);

$user = $client->oauth();


echo "<pre>";
$str = var_export($user, 1);
echo $str;
