<?php
require_once(__DIR__ . '/../modules/PrismSign.php');

// 执行Prism的验证签名
class PrismValidator {

    public function validate($request, $response) {

        // 获取sign的值并清理params
        $sign = $request->params['sign'];
        unset($request->params['sign']);

        var_dump($sign);

        $http_method  = $request->getMethod();
        $path         = $request->getPath();
        $headers      = $request->getHeaders();
        $query        = $request->getQuery();
        $postData     = $request->getPostData();
        $app_info     = $request->getAppInfo();
        $app_secret   = $app_info['client_id'];

        $new_headers = array();

//        foreach ($headers as $key => $value) {
//            $new_headers[str_replace('_', '-', $key)] = $value;
//        }

//        print_r($new_headers);

        echo PrismSign::produce($http_method, $path, $headers, $query, $postData, $app_secret);
        die;

        // 输入参数和Token进行校验
        if ( $sign == EcosSign::sign($request->params, '123456') )
            return;
        else
            $response->setError('Invalid Request', 'Sign is not valid.')->send();

    }

}
