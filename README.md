# Prsim PHP SDK
========================================

## 用途

实现Shopex Prism 的PHP版SDK供第三方使用

## 功能

- 提供HTTP API调用（GET/POST/PUT/DELETE方式）
- 提供Oauth认证
- (连接Websocket，可以发布/消费/应答消息)


## 用法


### 创建Prism Client实例对象
-----------------------------------------

    require_once('lib/Prism.php');

    $client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');


### 发起一个请求
-----------------------------------------
发起GET请求：

    echo $client->get('/test/test');
返回: 

    {httpMethod":"GET","responseTime":"10ms"}

### 发起GET/POST/PUT/DELETE请求
-----------------------------------------

*注意*：
- GET方法参数通过Query传递
- POST方法参数通过Body传递
- PUT方法参数通过Body传递
- DELETE方法参数通过Query传递
- 能够携带自定义Header
- path中的query会被合并到Query或者Body里

```php
    $headers = array(
        'X_API_UNITTEST1' => 'A',
        'X_API_UNITTEST2' => 'B'
    );

    $params = array(
        'param1' =>'C',
        'param2' =>'D',
    );

    $result['GET']      = $client->get('/test/test?param3=E&param4=F', $params, $headers);
    $result['POST']     = $client->post('/test/test?param3=E&param4=F', $params, $headers);
    $result['PUT']      = $client->put('/test/test?param3=E&param4=F', $params, $headers);
    $result['DELETE']   = $client->delete('/test/test?param3=E&param4=F', $params, $headers);


    print_r($result);
```

返回：
```php
    Array                                                                                                                                                   
    (                                                                                                                                                       
        [GET] => {"httpMethod":"GET","header1":"A","header2":"B","query":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}       

        [POST] => {"httpMethod":"POST","header1":"A","header2":"B","data":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}      

        [PUT] => {"httpMethod":"PUT","header1":"A","header2":"B","data":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"}        

        [DELETE] => {"httpMethod":"DELETE","header1":"A","header2":"B","query":{"param1":"C","param2":"D","param3":"E","param4":"F"},"responseTime":"10ms"} 

    )                                                                                                                                                       
```

### CURL和Socket连接
-----------------------------------------

可以通过：

    $client->requester = 'socket';
和

    $client->requester = 'curl';
    
来选择使用哪种http底层方法。(在CURL开启的情况下会自动优先启用CURL连接)  


### Oauth
-----------------------------------------

跳转到登录页面获取Token (需要在CGI环境下)

    $token = $client->oauth();
    
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
    
验证Token

    $token = $client->oauth($token);
    
验证成功，返回：

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
    
刷新Token

    $token = $client->refreshToken($token);
返回：

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



退出登录 (需要在CGI环境下)

    $client->logout();
    
返回

    "session is remove"    