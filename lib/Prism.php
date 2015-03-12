<?php
require_once('Sign.php');
require_once('Http.php');
require_once('Oauth.php');

Class Prism {

    public $base_url; //platform地址
    private $app_key; // key
    private $app_secret; // secret

    public $access_token; // access_token
    public $requester; // http包选择
    
    public $client; //prism对象
    public $oauth; //oauth对象
    
    function __construct($base_url, $app_key, $app_secret) {
        $this->base_url   = rtrim($base_url, '/');
        $this->app_key    = $app_key;
        $this->app_secret = $app_secret;
    }
    
    
    /**** 发起请求 ****
    */
    private function createRequest ($http_method, $path, $headers = array(), $params = null) {
        
        // 获取完整URL信息
        $url     = $this->base_url .'/'. ltrim($path, '/');
        $url_arr = parse_url($url);
  
        
        // 准备query, headers, postData
        $query = array();
        $postData = array();
        if(isset($url_arr['query'])) {
            parse_str($url_arr['query'], $query);
        }
        
        $headers['Pragma']        = 'no-cache';
        $headers['Cache-Control'] = 'no-cache';
        
        switch ($http_method) {
            
            case 'GET':
            case 'DELETE':
                if (is_array($params))
                    $query = array_merge($query, $params);
            break;  
            
            case 'POST':
            case 'PUT':
                $postData = array_merge($query, $params);
                $query = array();
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            break;
            
            default:
                $query = $params;
            
        }    
        
        $query['client_id']   = $this->app_key;
        $query['sign_method'] = 'md5';
        $query['sign_time']   = time();
        
        if($this->access_token)
            $headers["Authorization"] = "Bearer " . $this->access_token;

        // 生成数字签名
        $query['sign'] = Sign::produce($http_method, $url_arr['path'], $headers, $query, $postData, $this->app_secret);

        // https
        if ($url_arr['scheme'] == 'https') {
            $query                  = array();
            $query['client_id']     = $this->app_key;    
            $query['client_secret'] = $this->app_secret;      
        }

        // 拼装最后Url
        $final_url = preg_replace("/\?.*/", '', $url) . '?' . http_build_query($query);
        
                
        // 发起请求
        $http = new Http($this->requester);
        return $http->sendRequest($http_method, $final_url, $headers, $postData);   
        
    }
    
    /**** GET ****
    */
    public function get ($path, $params = null, $headers = null) {
        return $this->createRequest('GET', $path, $headers, $params);
    }
    /**** POST ****
    */   
    public function post ($path, $params = null, $headers = null) {
        return $this->createRequest('POST', $path, $headers, $params);
    }
    /**** PUT ****
    */
    public function put ($path, $params = null, $headers = null) {
        return $this->createRequest('PUT', $path, $headers, $params);
    }    
    /**** DELETE ****
    */
    public function delete ($path, $params = null, $headers = null) {
        return $this->createRequest('DELETE', $path, $headers, $params);
    }
    
    /**** OAUTH ****
    */
    public function oauth($token = null) {
        
        if (!$this->client)
            $this->client     = new Prism(str_replace('/api', '', $this->base_url), $this->app_key, $this->app_secret);
        if (!$this->oauth)
            $this->oauth      = new Oauth($this->client);
                                
        // 已有Token的话检查Token是否过期
        if ($token) {
            $checkSessionResult = $this->oauth->checkSession($token);
            if ($checkSessionResult->error == null)  //Token存在
                return $token;
            else //Token不存在
                $this->oauth->goToAuthPage();
        }
        
        if(!$_GET['code'])  // 跳转到验证页面 获取Token提取码(code)
            $this->oauth->goToAuthPage();
        else   // 提交code获取token 
            $token = $this->oauth->getToken($_GET['code']);
        
        if ($token->access_token)
            return $token;
        else
            $this->oauth->goToAuthPage();
        
    }
    
    /*
    刷新Token
    */
    public function refreshToken($token) {
        
        if (!$this->client)
            $this->client     = new Prism(str_replace('/api', '', $this->base_url), $this->app_key, $this->app_secret);
        if (!$this->oauth)
            $this->oauth      = new Oauth($this->client);
        
        return $this->oauth->refreshToken($token);
    }
    
    /*
    退出登录
    */
    public function logout ($redirect_uri = null) {
        
        if (!$this->client)
            $this->client     = new Prism(str_replace('/api', '', $this->base_url), $this->app_key, $this->app_secret);
        if (!$this->oauth)
            $this->oauth      = new Oauth($this->client);        
        
        $this->oauth->logout($redirect_uri);
    }
    
}


