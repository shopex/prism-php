<?php
class Oauth extends Requester {

    /**
    * OAUTH
    */
    public function oauth($redirect = null, $user_params = array()) {

        // 跳转到验证页面 获取Token提取码(code)
        if(@!$_GET['code'])
            $this->goToAuthPage($redirect, $user_params);
        // 提交code获取token
        else
            $token = $this->getToken($_GET['code']);

        if ($token->access_token)
            return $token;
        else
            $this->goToAuthPage($redirect);

    }

    /**
    * getToken
    * 通过Token提取码(code)获取Token
    */
    public function getToken($code) {

        $params = array(
            'code'       =>$code,
            'grant_type' => 'authorization_code'
        );

        $result = $this->createRequest('POST', '/oauth/token', '', $params);

        return json_decode($result);

    }

    /**
    * refreshToken
    * 通过refresh_token获取新的Token
    */
    function refreshToken($token) {

        $params = array(
            'refresh_token' => $token->refresh_token,
            'grant_type'    => 'refresh_token'
        );

        $result = $this->createRequest('POST', '/oauth/token', '', $params);
        return json_decode($result);

    }

    /**
    * checkSession
    * 验证token的session是否过期
    * {
    *    "result": true,
    *    "error": null
    * }
    */
    public function checkSession($token) {

        $params = array(
            'session_id'=>$token->session_id
        );

        $result = $this->createRequest('POST', '/platform/oauth/session_check', '', $params);
        return json_decode($result);

    }

    /**
    * 跳转到验证页面
    */
    public function goToAuthPage($redirect = null, $user_params = array()) {

        $params = array(
            'response_type' => 'code',
            'client_id' => $this->app_key,
        );

        if ($redirect)
            $params['redirect_uri'] = $redirect;
        else
            $params['redirect_uri'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $params = array_merge($params, $user_params);
        header('Location: ' . str_replace('/api', '', $this->base_url) . '/oauth/authorize'.'?'.http_build_query($params));
        exit;
    }

    /**
    * 退出登录
    */
    public function logout($redirect_uri = null) {

        if ($redirect_uri) {
            $params = array(
                'redirect_uri' => $redirect_uri,
            );
            header("Location: ". str_replace('/api', '', $this->base_url) . '/oauth/logout' . '?' . http_build_query($params));
        } else {
            header("Location: ". str_replace('/api', '', $this->base_url). '/oauth/logout');
        }

    }

    
    public function getAuthUrl($redirect = null) {
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->app_key,
        );

        if ($redirect)
            $params['redirect_uri'] = $redirect;
        else
            $params['redirect_uri'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return  str_replace('/api', '', $this->base_url) . '/oauth/authorize'.'?'.http_build_query($params);
    }

}
