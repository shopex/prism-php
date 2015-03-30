<?php
require_once(__DIR__ . '/../modules/Request.php');
require_once(__DIR__ . '/../modules/Response.php');
require_once(__DIR__ . '/../modules/PrismException.php');


class PrismServer {

    // GET路由方法
    public function get($path, $api, $function_name, $require_oauth = false) {
        $this->receive('GET', $path, $api, $function_name, $require_oauth);
    }

    // POST路由方法
    public function post($path, $api, $function_name, $require_oauth = false) {
        $this->receive('POST', $path, $api, $function_name, $require_oauth);
    }

    // PUT路由方法
    public function put($path, $api, $function_name, $require_oauth = false) {
        $this->receive('PUT', $path, $api, $function_name, $require_oauth);
    }

    // DELETE路由方法
    public function delete($path, $api, $function_name, $require_oauth = false) {
        $this->receive('DELETE', $path, $api, $function_name, $require_oauth);
    }

    /**
    * 接受请求
    * $method: 请求方法 (GET/POST/PUT/DELETE)
    * $path: 路由/method_id
    * $api: api对象(处理器)
    * $function_name: api对象处理方法
    * $require_oauth: 是否需要oauth验证
    */
    private function receive ($method, $path, $api, $function_name, $require_oauth = false) {

        $response = new Response($this->request_id);

        // method判断
        if($method != $this->method)
            return;

        // path判断
        if($path != $this->path)
            return;

        // api对象(处理器)判断
        if( !is_object($api) )
            $response->send('error', 'Method not found', 'Handling object is not found on server');

        // 处理方法判断
        if ( !is_callable(array($api, $function_name)) )
            $response->send('error', 'Method not found', 'Handling action is not found on server');

        // oauth判断
        if ( $require_oauth && empty($this->oauth_info) )
            $response->send('error', 'Invalid Request', 'Oauth is required');

        call_user_func(
            array($api, $function_name),
            $this->params, $this->headers, $this->request_id, $this->oauth_info, $response
        );

    }

    /**
    * 分发请求到对象的方法
    */
    public function dispatch ($handler, $require_oauth = false) {

        // 检查handler
        if (!is_string($handler))
            throw new PrismException('No handler given to dispatcher');

        // 创建Request和Response
        $request  = new Request();
        $response = new Response($request->getRequestID());

        // oauth判断
        if ( $require_oauth && empty($request->oauth_info) )
            $response->send('error', 'Invalid Request', 'Oauth is required');

        // 解析对象名和方法名
        list($class_name, $action_name) = explode('@', $handler);

        //
        call_user_func(array(new $class_name, $action_name), $request, $response);

    }

}
