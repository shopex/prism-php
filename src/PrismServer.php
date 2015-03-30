<?php
require_once(__DIR__ . '/../modules/Request.php');
require_once(__DIR__ . '/../modules/Response.php');
require_once(__DIR__ . '/../modules/PrismException.php');

interface PrismServerInterface {

    // GET路由方法
    public function get($path, $handler, $require_oauth);

    // POST路由方法
    public function post($path, $handler, $require_oauth);

    // PUT路由方法
    public function put($path, $handler, $require_oauth);

    // DELETE路由方法
    public function delete($path, $handler, $require_oauth);

    // 把请求分发到处理类的方法
    public function dispatch($handler, $require_oauth);
}

class PrismServer implements PrismServerInterface {

    // GET路由方法
    public function get($path, $handler, $require_oauth = false) {

        $request = new Request();

        if ($request->getMethod() != 'GET')
            return;

        if ($request->getPath() != $path)
            return;

        $this->dispatch($handler, $require_oauth, $request);

    }

    // POST路由方法
    public function post($path, $handler, $require_oauth = false) {

        $request = new Request();

        if ($request->getMethod() != 'POST')
            return;

        if ($request->getPath() != $path)
            return;

        $this->dispatch($handler, $require_oauth, $request);

    }

    // PUT路由方法
    public function put($path, $handler, $require_oauth = false) {

        $request = new Request();

        if ($request->getMethod() != 'PUT')
            return;

        if ($request->getPath() != $path)
            return;

        $this->dispatch($handler, $require_oauth, $request);

    }

    // DELETE路由方法
    public function delete($path, $handler, $require_oauth = false) {

        $request = new Request();

        if ($request->getMethod() != 'DELETE')
            return;

        if ($request->getPath() != $path)
            return;

        $this->dispatch($handler, $require_oauth, $request);

    }

    /**
    * 分发请求到对象的方法
    */
    public function dispatch ($handler, $require_oauth = false, $request = null) {

        // 检查handler
        if (!is_string($handler))
            throw new PrismException('No handler given to dispatcher');

        // 创建Request和Response
        if (!$request)
            $request  = new Request();
        $response = new Response($request->getRequestID());

        // oauth判断
        if ( $require_oauth && !$request->getOauth() )
            $response->setError('Invalid Request', 'Oauth is required')->send();

        // 解析对象名和方法名
        list($class_name, $action_name) = explode('@', $handler);

        // 执行方法
        call_user_func(array(new $class_name, $action_name), $request, $response);

    }

}
