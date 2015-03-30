<?php
// 先起服务器 php -S 0.0.0.0:8080 server.php
require_once(__DIR__.'/../src/Provider.php');


$server = new Provider(); // 创建服务端Provider实例
$api    = new Api(); // 创建API处理器(handler/controller)实例

/**
* 添加路由，Provider会去寻找API下的fetchStudent方法来处理请求
* $method_id/path 客户端请求里携带的参数，这里为id=>43
* $api API处理器(handler/controller)的实例
* $action/$function_name 需要调用的处理方法
* $require_oauth: 是否需要oauth验证(默认为false)
*/
$server->get( '/student', $api, 'fetchStudent', false );


$server->post( '/student', $api, 'createStudent', true );
$server->put( '/student', $api, 'modifyStudent', true );
$server->delete( '/student', $api, 'deleteStudent', true );


// API处理器(handler/controller) 可以自己定义具体的名称
class Api {

    var $model;

    // 为测试方便添加的模型层和测试数据
    function __construct () {
        $this->model = new Model();
    }

    /**
    * $params 客户端请求里携带的参数，这里为id=>43
    * $headers 客户端请求里携带的自定义头信息
    * $request_id Prism返回的request id
    * $oauth_info 客户端请求里携带的oauth头信息
    * $response 用于返回标准化的JSONRPC 2.0的JSON格式响应
    */
    function fetchStudent($params, $headers, $request_id, $oauth_info, $response) {
        $response->send('success', $this->model->students[$params['id']]);
    }

    function createStudent($params, $headers, $request_id, $oauth_info, $response) {
        $response->send('success', 'student:44 is created');
    }

    function modifyStudent($params, $headers, $request_id, $oauth_info, $response) {
        $response->send('success', 'student:' . $params['id'] . ' is modified');
    }

    function deleteStudent($params, $headers, $request_id, $oauth_info, $response) {
        if (!$params['id'])
            $response->send('error', 'missing id');
        $response->send('success', 'student:' . $params['id'] . ' is deleted');
    }

}


// 为测试方便添加的模型层和测试数据
class Model {
    var $students = array();

    function __construct () {

        $this->students[42] = array(
            'id'=>42,
            'name'=>'Jack Dawson',
            'age'=>21,
            'desc'=>'A penniless artist.',
        );
        $this->students[43] = array(
            'id'=>43,
            'name'=>'Rose DeWitt Bukater',
            'age'=>17,
            'desc'=>'Is forced into an engagement to 30-year-old Cal Hockley.',
        );

    }
}
