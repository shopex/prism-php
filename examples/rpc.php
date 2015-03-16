<?php
require_once(__DIR__.'/../lib/Prism.php');

//$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
$client = new Prism($url = 'http://127.0.0.1:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

$headers = array(
    'X_API_UNITTEST1' => 'A',
    'X_API_UNITTEST2' => 'B'
);

$params = array(
	'param1' =>'C',
	'param2' =>'D',
);

$client->setRequester('socket');
$client->setRequester('curl');

$result = array();

$result['GET']      = $client->get('/test/test?param3=E&param4=F', $params, $headers);
$result['POST']     = $client->post('/test/test?param3=E&param4=F', $params, $headers);
$result['PUT']      = $client->put('/test/test?param3=E&param4=F', $params, $headers);
$result['DELETE']   = $client->delete('/test/test?param3=E&param4=F', $params, $headers);

print_r($result);

/*
返回：
Array
(
    [GET] => {"httpMethod":"GET","header1":"A","header2":"B","query":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

    [POST] => {"httpMethod":"POST","header1":"A","header2":"B","data":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

    [PUT] => {"httpMethod":"PUT","header1":"A","header2":"B","data":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

    [DELETE] => {"httpMethod":"DELETE","header1":"A","header2":"B","query":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}

)
*/
