# 发起GET/POST/PUT/DELETE请求 #

## 注意 ##

- GET方法参数通过Query传递
- POST方法参数通过Body传递
- PUT方法参数通过Body传递
- DELETE方法参数通过Query传递
- 能够携带自定义Header
- (path中的query会被合并到Query或者Body里)

## 发起请求 ##
准备一些自定义Header信息

    $headers = array(
        'X_API_TEST1' => 'A',
        'X_API_TEST2' => 'B'
    );

通过category获取AppleStore产品列表 (GET请求) 利用path进行分 /api/path

    $params = array(
	    'category' =>'mac'
    );
    $r = $client->get('/apple_store/get_list', $params, $headers);

通过category获取AppleStore产品列表 (POST请求) 利用请求参数进行分发 method

    $params = array(
        'method' =>'get_list',
    	'category' =>'mac'
    );

    $r = $client->post('/apple_store', $params, $headers);

## CURL和Socket连接

为了提高兼容性我们提供了方法来让用户选择使用哪一种HTTP/HTTPS请求方式(在CURL开启的情况下会自动优先启用CURL连接)  ：

    $client->setRequester('socket');
    $client->setRequester('curl');
    
## 可以携带Oauth的Access Token

	$client->access_token = 'c4t6q5rh6fysu5v5ww5xenv4';

[返回](index.md)