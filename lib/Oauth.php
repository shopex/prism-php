<?php
class Oauth {

    var $prism;
    
    function __construct ($prism) {
        $this->prism = $prism;
    }
    
    /* getToken
    通过Token提取码(code)获取Token
    */
    function getToken($code) {

        $param = array(
            'code'        =>$code,
            'grant_type'  => 'authorization_code'
        );
        
        $result = $this->prism->post('/oauth/token', $param);
        return json_decode($result);
    
    }
    /* refreshToken
    通过refresh_token获取新的Token
    */
    function refreshToken($token) {
        
        $param = array(
            'refresh_token' => $token->refresh_token,
            'grant_type'    => 'refresh_token'
        );
        
        $result = $this->prism->post('/oauth/token', $param);
        return json_decode($result);
        
    }
    
    /* checkSession
    验证token的session是否过期
    {
        "result": true,
        "error": null
    }
    */
    public function checkSession($token) {
        
        $param = array(
            'session_id'=>$token->session_id
        );
        
        $result = $this->prism->post('/api/platform/oauth/session_check', $param);
        return json_decode($result);
        
    }    
    
    /*
    跳转到验证页面
    */
    public function goToAuthPage() {

        $params = array(
            'response_type' => 'code',
            'client_id' => 'pufy2a7d',
            'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
        );    
        
        header('Location: ' . $this->prism->base_url . '/oauth/authorize'.'?'.http_build_query($params));
        
    }
    
    /*
    退出登录
    */
    public function logout($redirect_uri = null) {
        
        if ($redirect_uri) {
            $params = array(
                'redirect_uri' => $redirect_uri,
            );   
            header("Location: ". $this->prism->base_url . '/oauth/logout' . '?' . http_build_query($params));
        } else {
            header("Location: ". $this->prism->base_url . '/oauth/logout');
        }
        
    }

}
