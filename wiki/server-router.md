# Prism Server With Router #

----------
Prism Server有两种使用方法，一种是走SDK的路由，第二种是当你有自己的路由，可以直接使用分发器把请求分发到你的处理器上。
这里我们先介绍使用SDK内置路由的情况。

## 创建对象 ##
创建服务端实例

	require_once(__DIR__.'/../src/PrismServer.php');	
	$server = new PrismServer();

## 注册API方法 ##

- $path/methodID:路由地址(path)或者routing_key
- $handler:处理器的类名@方法名
- $require_oauth:是否需要oauth验证(默认为false)

    	$server->get('/ping', 'AppleStore@pong');
    	$server->get('/get_list', 'AppleStore@getList', true);
	

## 处理器 ##
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
	    }
	
	}

不发送请求，直接获取请求里的内容(JSON)

	echo $response->getJSON();

成功时返回结果：

	{
	    "jsonrpc":"2.0",
	    "result":["ipad","iphone","macbook"],
	    "id":"3mpm4wr76dtmejev"
	}
	    
失败时返回结果：

    {
        "jsonrpc":"2.0",
        "error":{"code":"-32602","message":"Invalid params","data":"No category given"},
        "id":"ifepmss42vewq2b5"
    }

## Request对象 ##

获取请求中的Params
> public function getParams();

获取请求中的Request ID
> public function getRequestID();

获取用户登录的信息
> public function getOauth();

获取应用的信息
> public function getAppInfo();

获取请求者IP
> public function getCallerIP();

获取自定义头信息
> public function getHeaders();

获取请求的类型GET/POST/PUT/DELETE
> public function getMethod();

获取请求的地址 (path)
> public function getPath();

## Response对象 ##

添加自定义头 比如'Content-Type： text/json;charset=utf8'
> public function setHeader($key, $value);

设置返回内容, $result可以是字符串数组对象等
> public function setResult($result);

设置错误内容,会自动添加错误码 $message：错误类型, $data: 错误信息
> public function setError($message, $data);

获取$headers 包括自定义的Header
> public function getHeaders();

获取$request_id
> public function getRequestID();

获取JSON格式的响应内容(JSONRPC2.0)
> public function getJSON();

发送响应 会执行exit()
> public function send();

## 利用请求参数进行分发时设置routing key ##

	$server->setRoutingKey('method');

**发送请求时params要携带'method' =>'get_list'**

## Middleware ##


使用Logger来记录日志

	require_once(__DIR__.'/../middlewares/Logger.php');
	$server->uses('Logger@show');

Logger方法

    public function show ($request, $response) {
        echo @date("Y-m-d H:i:s", time()) . " request id: " . $request->getRequestID() . "\n";
    }

使用Ecos的验签middleware来验证

	require_once(__DIR__.'/../middlewares/EcosValidator.php');
	$server->uses('EcosValidator@validate');

验签方法

    public function validate($request, $response) {

        // 获取sign的值并清理params
        $sign = $request->params['sign'];
        unset($request->params['sign']);


        // 输入参数和Token进行校验
        if ( $sign == EcosValidator::sign($request->params, '123456') )
            return;
        else
            $response->setError('Invalid Request', 'Sign is not valid.')->send();

    }
 
 [返回](index.md)