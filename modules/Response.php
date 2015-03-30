<?php
// 给PHP-SDK Provider来创建符合JSON RPC规范的JSON响应
interface ResponseInterface {

    // 添加自定义头 比如'Content-Type： text/json;charset=utf8'
    public function setHeader($key, $value);

    // 设置返回内容, $result可以是字符串数组对象等
    public function setResult($result);

    // 设置错误内容,会自动添加错误码 $message：错误类型, $data: 错误信息
    public function setError($message, $data);

    // 获取$headers 包括自定义的Header
    public function getHeaders();

    // 获取$request_id
    public function getRequestID();

    // 获取JSON格式的响应内容(JSONRPC2.0)
    public function getJSON();

    // 发送响应 会执行exit()
    public function send();
}

class Response implements ResponseInterface {

    private $request_id;
    private $headers = array();
    private $result;
    private $error = array();
    private $error_types = array(

        // Invalid JSON was received by the server.An error occurred on the server while parsing the JSON text.
        'Server error' => '-32000',

        // The JSON sent is not a valid Request object.
        'Invalid Request' => '-32600',

        // The method does not exist / is not available.
        'Method not found' => '-32601',

        // Invalid method parameter(s).
        'Invalid params' => '-32602',

        // Internal JSON-RPC error.
        'Internal error' => '-32603',

        // Reserved for implementation-defined server-errors.
        'Parse error' => '-32700',

    );

    /**
    * $reuqest_id为必须
    */
    public function __construct($request_id = null) {

        if (!$request_id)
            throw new PrismException('No request_id given to Response');

        $this->request_id = $request_id;
        $this->setHeader('Content-Type', 'text/json;charset=utf8');
        $this->setHeader('X-Request-Id', $request_id);

    }

    public function setHeader($key, $value) {
        $this->headers[$key] = $value;
        header("{$key}: {$value}");

        return $this;
    }

    public function setResult($result) {
        $this->result = $result;

        return $this;
    }

    public function setError($message, $data) {
        $this->error['code']    = $this->error_types[$message];
        $this->error['message'] = $message;
        $this->error['data']    = $data;

        return $this;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getRequestID() {
        return $request_id;
    }

    public function getJSON() {

        $result             = array();
        $result['jsonrpc']  = '2.0';

        if($this->result)
            $result['result']   = $this->result;

        if ($this->error)
            $result['error']  = $this->error;

        $result['id'] = $this->request_id;

        return json_encode($result);

    }

    public function send() {

        echo $this->getJSON();
        exit();

    }

}
