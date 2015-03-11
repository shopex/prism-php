<?php

require_once('../lib/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


// 跳转到登录页面获取Token (需要在CGI环境下)
$token = $client->oauth();

/*
返回：
stdClass Object
(
    [access_token] => nkmabee4wxmhgjsqbeo2eg4g
    [data] => stdClass Object
        (
            [@id] => test
            [id] => 1
            [name] => test
            [passwd] => test
        )

    [expires_in] => 1425962899
    [refresh_expires] => 1428551299
    [refresh_token] => iaysxo7zyiwnk743uyyzaa7w3ee4nrea
    [session_id] => npqsm6wxlobp745vqftzlu
)
*/

// 验证Token
//$token = $client->oauth($token);
/*

stdClass Object
(
    [access_token] => ca7wpnk42p6eaelp42rezzof
    [data] => stdClass Object
        (
            [@id] => test
            [id] => 1
            [name] => test
            [passwd] => test
        )

    [expires_in] => 1425963121
    [refresh_expires] => 1428551521
    [refresh_token] => 4zwrrcdhsjn22ptf6dj5ynrxbqddtahj
    [session_id] => npqsm6wxlobp745vqftzlu
)
*/

// 刷新Token
//$token = $client->refreshToken($token);
/*
stdClass Object
(
    [access_token] => c4t6q5rh6fysu5v5ww5xenv4
    [data] => stdClass Object
        (
            [@id] => test
            [id] => 1
            [name] => test
            [passwd] => test
        )

    [expires_in] => 1425963155
    [refresh_expires] => 1428551555
    [refresh_token] => lqbjwmadkdbhxtz2jkbna4a3xaqsgfui
    [session_id] => npqsm6wxlobp745vqftzlu
)
*/
// 退出登录
//$client->logout();
// "session is remove"

echo "<pre>";
print_r($token);


// 如果有Toekn可以携带Token发起请求
$client->access_token = $token->access_token;
echo $client->get('/test/test');
// {"httpMethod":"GET","oauth":"%40id=test&id=1&name=test&passwd=test","responseTime":"10ms"}
