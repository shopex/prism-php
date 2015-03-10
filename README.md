# Prsim PHP SDK #

## 用途 ##
实现Shopex Prism 的PHP版SDK供第三方使用

## 功能 ##
- 提供HTTP API调用（GET/POST/PUT/DELETE方式）
- 提供Oauth认证
- (连接Websocket，可以发布/消费/应答消息)


## 用法 ##

### 创建Prism Client实例对象 ###

```php
require_once('lib/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
```

### 发起GET/POST/PUT/DELETE请求 ###

注意：
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

// 如果有Oauth Token可以携带Token发起请求
// $client->access_token = $token->access_token;

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