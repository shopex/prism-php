# Prsim PHP SDK #

## 用途 ##
实现shopex Prism 的Java版SDK供第三方使用

## 功能 ##
- 提供http API调用（GET/POST/PUT/DELETE方式）
- 提供oauth认证
- (连接Websocket，可以发布/消费/应答消息)

## 用法 ##
- 创建Prism Client实例对象


```php
require_once('lib/Prism.php');

$client = new Prism($url = 'http://192.168.51.50:8080/api', $key = 'pufy2a7d', $secret = 'skqovukpk2nmdrljphgj');
```