<?php
// 先起服务器 php -S 0.0.0.0:8080 server.php
require_once(__DIR__.'/../src/PrismServer.php');

$server = new PrismServer(); // 创建服务端实例

/**
* $handler:         类名@方法名
* $require_oauth:   是否需要oauth验证(默认为false)
* $request:         可以使用实现了PrismRequest接口的Request对象,这样就能伪造请求了
*/
$server->dispatch('AppleStore@getList', false);


class AppleStore {

    public function getList($request, $response) {

        $params = $request->getParams();
        // $params = 'category' => 'mac'

        $store = array(
            'mac' => array('macbook', 'macbook pro', 'macbook air'),
            'mobile' => array('ipad', 'iphone', 'ipod')
        );

        $result = $store[$params['category']];

        if ($params['category'])
            $response->setResult($result);
        else
            $response->setError('Invalid params', 'No category given');

        $response->send();

        /*
        echo $response->getJSON();
        {
            "jsonrpc":"2.0",
            "result":["ipad","iphone","macbook"],
            "id":"3mpm4wr76dtmejev"
        }
        or
        {
            "jsonrpc":"2.0",
            "error":{"code":"-32602","message":"Invalid params","data":"No category given"},
            "id":"ifepmss42vewq2b5"
        }
        */
    }

}
