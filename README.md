## Prsim PHP SDK ##
实现Shopex Prism 的PHP版SDK供第三方使用

[文档](wiki/index.md)

## Release Notes ##
- 2015-04-02 修复了Client params 传递 null, false, 0时验签失败的bug，更改了false和null的PHP签名方法。
- 2015-04-03 完成了 Prism Server PHP SDK的Prism Validate中间件，可以检验Prism发出的Prsim MD5签名。
- 2015-04-24 增加socket连接方式 $client = new PrismClient($url, $key, $secret, $socket="unix:///tmp/api_provider.sock");
