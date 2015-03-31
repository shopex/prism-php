<?php
require_once(__DIR__ . '/../modules/PrismSign.php');

// 执行Prism的验证签名
class PrismValidator {

    public function validate($request, $response) {

        // 获取sign的值并清理params
        $sign = $request->params['sign'];
        unset($request->params['sign']);

        var_dump($sign);

        print_r($request);

        echo PrismSign::produce($http_method, $path, $headers, $query, $postData, $this->app_secret);
        die;

        // 输入参数和Token进行校验
        if ( $sign == EcosSign::sign($request->params, '123456') )
            return;
        else
            $response->setError('Invalid Request', 'Sign is not valid.')->send();

    }

}
