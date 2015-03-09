<?php

require_once('lib/Prism.php');
require_once('lib/Oauth.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

$headers = array(
    'X_API_UNITTEST1' => 'A',
    'X_API_UNITTEST2' => 'B'
);

$params = array(
	'param1' =>'C',
	'param2' =>'D',
);

// oauth
// code: ifvdikj4rygeqy2lsaqm
$token = '
{                                                     
  "access_token": "67h7e2psabrjoplgonlalao7",         
  "data": {                                           
    "@id": "test",                                    
    "id": "1",                                        
    "name": "test",                                   
    "passwd": "test"                                  
  },                                                  
  "expires_in": 1425545562,                           
  "refresh_expires": 1428133962,                      
  "refresh_token": "xiho7f2mpixdkcthkxkxz7kosmhi2upc",
  "session_id": "agdmrwdvzosbq2kpy26g25" 
}                                                     
';
//$token = null;

$token = json_decode($token);



//$client->logout();
//$token = $client->oauth();
//$token = $client->refreshToken($token);
//print_r($token);

//$token = $client->oauth($token);

//echo "<pre>";
//print_r($token);

$client->access_token = $token->access_token;
$result1  = $client->get('/test/test?param3=E&param4=F', $params, $headers);
$result2  = $client->post('/test/test?param3=E&param4=F', $params, $headers);
$result3  = $client->put('/test/test?param3=E&param4=F', $params, $headers);
$result4  = $client->delete('/test/test?param3=E&param4=F', $params, $headers);

echo $result1;
echo $result2;
echo $result3;
echo $result4;


exit();