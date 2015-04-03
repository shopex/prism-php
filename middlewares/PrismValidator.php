<?php
require_once(__DIR__ . '/../modules/PrismSign.php');

// 执行Prism的验证签名
class PrismValidator {

    public function validate($request, $response) {

        // 获取sign的值并清理params
        $sign = $request->params['sign'];
        unset($request->params['sign']);

        $http_method  = $request->getMethod();
        $path         = $request->getPath();
        $headers      = $request->getHeaders();
        $query        = $request->getQuery();
        $postData     = $request->getPostData();
        $app_info     = $request->getAppInfo();
        $app_secret   = '';

        unset($query['sign']);

        // 输入参数和Token进行校验
        if ( $sign == PrismSign::produce($http_method, $path, $headers, $query, $postData, $app_secret) )
            return;
        else
            $response->setError('Invalid Request', 'Sign is not valid.')->send();

    }

}
