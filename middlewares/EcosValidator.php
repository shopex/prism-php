<?php
// 执行Ecos的验证签名
class EcosValidator {

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

    static public function isValidate($params, $token) {
        $sign = $params['sign'];
        unset($params['sign']);
        return ($sign == self::sign($params, $token)) ? true : false;
    }

    static public function sign($params, $token) {
        return strtoupper(md5(strtoupper(md5(self::assemble($params))).$token));
    }

    static function assemble($params) {
        if(!is_array($params))  return null;
        ksort($params, SORT_STRING);
        $sign = '';
        foreach($params AS $key=>$val){
            if(is_null($val))   continue;
            if(is_bool($val))   $val = ($val) ? 1 : 0;
            $sign .= $key . (is_array($val) ? self::assemble($val) : $val);
        }
        return $sign;
    }

}
