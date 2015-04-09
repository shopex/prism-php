# Prism Server Without Router #

----------
Prism Server有两种使用方法，一种是走SDK的路由，第二种是当你有自己的路由，可以直接使用分发器把请求分发到你的处理器上。
这里我们先介绍使用自己的路由直接分发的情况。

## 创建对象 ##
创建服务端实例

	require_once(__DIR__.'/../src/PrismServer.php');
	$server = new PrismServer();

## 分发到处理器 ##

- $handler:         类名@方法名
- $require_oauth:   是否需要oauth验证(默认为false)
- $request:         可以使用实现了PrismRequest接口的Request对象,这样就能伪造请求了

		$server->dispatch('AppleStore@getList', false);

处理器

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

	    }
	
	}

成功时返回：

	{
	    "jsonrpc":"2.0",
	    "result":["ipad","iphone","macbook"],
	    "id":"3mpm4wr76dtmejev"
	}

失败时返回：

	{
	    "jsonrpc":"2.0",
	    "error":{"code":"-32602","message":"Invalid params","data":"No category given"},
	    "id":"ifepmss42vewq2b5"
	}

[返回](index.md)