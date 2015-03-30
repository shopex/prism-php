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
