<?php
require_once('../lib/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

$headers = array(
    'X_API_UNITTEST1' => 'A',
    'X_API_UNITTEST2' => 'B'
);

$params = array(
	'param1' =>'C',
	'param2' =>'D',
);

// 如果有Toekn可以携带Token发起请求
// $client->access_token = $token->access_token;

$result['GET']      = $client->get('/test/test?param3=E&param4=F', $params, $headers);
$result['POST']     = $client->post('/test/test?param3=E&param4=F', $params, $headers);
$result['PUT']      = $client->put('/test/test?param3=E&param4=F', $params, $headers);
$result['DELETE']   = $client->delete('/test/test?param3=E&param4=F', $params, $headers);


print_r($result);
