# 使用Oauth认证

----------


## 跳转到登录页面获取Token (需要Apache或Nginx等HTTP Server)

引导用户跳转到平台的登录页面(需连接Prism)

    $token = $client->oauth();
    
也可以加上成功后跳会的页面
    
    $token = $client->oauth($redirect = 'http://www.xxx.com');
   
    
![login](https://git.ishopex.cn/uploads/prism-sdk/prism-php/75451bbd9b/login.png)


登录(需要在页面上输入用户名密码)成功后会返回Token对象：

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
    
   
## 验证获取到的Token是否有效
   

    $client->checkSession($token);
    
验证成功，返回：

    stdClass Object
    (
        [result] => 1
        [error] => 
    )

## 带着获取到的Token访问API

	$client->access_token = 'nkmabee4wxmhgjsqbeo2eg4g';
	$client->post('/apple_store');

[返回](index.md)