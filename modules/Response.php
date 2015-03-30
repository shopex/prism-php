<?php
// 给PHP-SDK Provider来创建符合JSON RPC规范的JSON响应
class Response {

    private $request_id;
    private $result;
    private $error;
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
    public function __construct($request_id) {
        $this->request_id = $request_id;
        die($request_id);
    }

    public function setHeader() {

    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function getRequestID() {
        return $request_id;
    }

    public function getJSON() {

    }

    /**
    *
    */
    public function send() {

        $result             = array();
        $result['jsonrpc']  = '2.0';
        $result['result']   = $this->result;

//        if ($status == 'error') {
//            $error['code']    = $this->error_arr[$message];
//            $error['message'] = $message;
//            $error['data']    = $data;
//            $result['error']  = $error;
//        }

        $result['id'] = $this->request_id;

        echo json_encode($result);
        exit();

    }

}
