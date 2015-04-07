<?php
// 先起服务器 php -S 0.0.0.0:8080 server.php
require_once(__DIR__.'/../src/PrismServer.php');
require_once(__DIR__.'/../middlewares/EcosValidator.php');
require_once(__DIR__.'/../middlewares/PrismValidator.php');
require_once(__DIR__.'/../middlewares/Logger.php');


$server = new PrismServer(); // 创建服务端实例

//$server->setRoutingKey('method'); // 利用请求参数进行分发时一定要设置routing key

//$server->uses('PrismValidator@validate'); // 使用Prism的验签middleware来验证
//$server->uses('EcosValidator@validate'); // 使用Ecos的验签middleware来验证
//$server->uses('Logger@show'); // 使用Logger来记录日志

/**
* $path:            路由地址(path)
* $handler:         类名@方法名
* $require_oauth:   是否需要oauth验证(默认为false)
*/
$server->get('/ping', 'AppleStore@pong');
$server->get('/get_list', 'AppleStore@getList', true);
//$server->post('get_list', 'AppleStore@getList', true);


class AppleStore {

    public function pong() {
        echo "pong";
    }

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
