<?php
// 给PHP-SDK Provider来创建符合JSON RPC规范的JSON响应
class Response {

    var $reuqest_id;
    var $error_arr = array(

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
    public function __construct($reuqest_id) {
        $this->reuqest_id = $reuqest_id;
    }

    /**
    * $status response类型 success/error
    * $message succes是返回成result，error时返回成error message
    * $data error时返回error data
    */
    public function send($status, $message = null, $data = null) {

        if ($status != 'success' && $status != 'error')
            throw new PrismException("Status of response can only be 'success' of 'error'");


        $result             = array();
        $error              = array();
        $result['jsonrpc']  = '2.0';

        if ($status == 'success')
            $result['result'] = $message;

        if ($status == 'error') {
            $error['code']    = $this->error_arr[$message];
            $error['message'] = $message;
            $error['data']    = $data;
            $result['error']  = $error;
        }

        $result['id'] = $this->reuqest_id;

        echo json_encode($result);
        exit();

    }

}
