# 快速开始 #
----------


## 创建Prism Client实例对象 ##
首先我们要引用PHP Prism SDK

    require_once(__DIR__ . '/src/PrismClient.php');

创建对象：

    $client = new PrismClient($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');

三个必要参数：

- url: 我们测试平台的地址(会先连接到Prism再到你提交的API服务上获取运行结果)。
  - 如果只是在本地测试，可以用http://127.0.0.1:8000 (需要自己开启的API服务)。
  - 如果使用HTTPS加密方法，请注意你的url应该是类似https://192.168.51.50:443的格式。
- key: 也叫cliend_id，在平台上注册你的API时会提供给你。
- secret: 密匙，在平台上注册你的API时会提供给你。


## 发起一个请求
发起GET请求：

    echo $client->get('/apple_store/ping');

返回: 

    "pong"


注：具体的返回结果和API的具体实现有关，一般会以JSON格式返回结果。


[返回](index.md)