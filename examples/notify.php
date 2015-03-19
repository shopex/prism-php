<?php

$data   = 'hello world!';  //data to be send

$head = "GET / HTTP/1.1"."\r\n".
            "Upgrade: WebSocket"."\r\n".
            "Connection: Upgrade"."\r\n".
            "Origin: http://192.168.51.50:8080/api/platform/notify"."\r\n".
            "Host: 192.168.51.50:8080"."\r\n".
            "Content-Length: ".strlen($data)."\r\n"."\r\n";



//http://192.168.51.50:8080/api


//WebSocket handshake
$sock = fsockopen('192.168.51.50', '8080', $errno, $errstr, 2);

$write = fwrite($sock, $head ) or die('error:'.$errno.':'.$errstr);

var_dump($sock);
var_dump($write);

$headers = fread($sock, 2000);
fwrite($sock, "\x00$data\xff" ) or die('error:'.$errno.':'.$errstr);
$wsdata = fread($sock, 2000);  //receives the data included in the websocket package "\x00DATA\xff"
fclose($sock);

print_r($headers);
print_r($wsdata);

//        $headers = array('Upgrade'=>'websocket',
//                      'Sec-Websocket-Key' => $this->wskey(),
//                      'Sec-WebSocket-Version' => 13,
//                      'Sec-WebSocket-Protocol' => 'chat',
//                      'Origin' => $this->client->base_url.'/platform/notify',
//                      'Connection'=>'Upgrade');
//
//        $result = $this->client->get('platform/notify', array(), $headers);
