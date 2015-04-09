# Prsim PHP SDK

----------

实现Shopex Prism 的PHP版SDK供第三方使用


## 基本介绍 ##

- 提供HTTP API调用（GET/POST/PUT/DELETE方式）
- 提供Oauth认证
- 连接Websocket，可以发布/消费/应答消息
- Prism Server 帮助你快速构建API接口服务


## 安装 ##
PHP Prism SDK下载后直接使用无需安装，目前没有PHP版本要求。 (如果你要使用到所有功能，那么需要Apache或Nginx等HTTP Server支持

## Prism Client 用法 ##
- [快速开始](get.md)
- [详细请求方法GET/POST/PUT/DELETE](rpc.md)
- [Oauth认证](oauth.md)
- [Notify](notify.md)


## Prism Server 用法 ##
- [带路由的Server](server-router.md)
- [不带路由的Server](server-norouter.md)