<?php
// 先起服务器 php -S 0.0.0.0:8080 server.php
require_once(__DIR__.'/../src/PrismServer.php');


$server = new PrismServer(); // 创建服务端实例

/**
* $handler:         类名@方法名
* $require_oauth:   是否需要oauth验证(默认为false)
*/
$server->dispatch('AppleStore@getList', false);


class AppleStore {

    public function getList($request, $response) {

//        print_r($request->getParams());

        $list = array('ipad', 'iphone', 'macbook');

        $response->setResult($list);
        $response->send();

        // 返回{"jsonrpc":"2.0","result":["ipad","iphone","macbook"],"id":"3mpm4wr76dtmejev"}
    }

}
