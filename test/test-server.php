<?php

//echo "<pre>";
//print_r($_SERVER["REQUEST_URI"]);die;

if ( strstr($_SERVER["REQUEST_URI"], '/oauth/authorize') )
    oauth_page();
elseif ( strstr($_SERVER["REQUEST_URI"], '/oauth/token') )
    produce_token();
elseif ( strstr($_SERVER["REQUEST_URI"], '/platform/oauth/session_check') )
    check_session_id();
elseif ( strstr($_SERVER["REQUEST_URI"], '/oauth/logout') )
    logout();
else
    api();


function api() {

    //$time_start = microtime(true);
    $time_start = $_SERVER['REQUEST_TIME_FLOAT'];
    usleep(10*1000);
    //sleep(5);
    $time_end = microtime(true) - $time_start;
    $time_taken = round($time_end, 3)*1000 . "ms";

    $result  = array();
    parse_str(file_get_contents('php://input'), $data);

    $result['httpMethod']           = $_SERVER['REQUEST_METHOD'];

    if (@$_GET['client_id'])
        unset($_GET['client_id']);
    if (@$_GET['sign_method'])
        unset($_GET['sign_method']);
    if (@$_GET['sign_time'])
        unset($_GET['sign_time']);
    if (@$_GET['sign'])
        unset($_GET['sign']);

//    print_r($_GET);die;

    if (@$_SERVER['HTTP_AUTHORIZATION'])
        $result['oauth']            = $_SERVER['HTTP_AUTHORIZATION'];
    if (@$_SERVER['HTTP_X_API_OAUTH'])
        $result['oauth']            = $_SERVER['HTTP_X_API_OAUTH'];
    if (@$_SERVER['HTTP_X_REQUEST_ID'])
        $result['requestId']            = $_SERVER['HTTP_X_REQUEST_ID'];
    if (@$_SERVER['HTTP_X_API_UNITTEST1'])
        $result['header1']          = $_SERVER['HTTP_X_API_UNITTEST1'];
    if (@$_SERVER['HTTP_X_API_UNITTEST2'])
        $result['header2']          = $_SERVER['HTTP_X_API_UNITTEST2'];
    if (@$_GET)
        $result['query']            = $_GET;
    if (@$data)
        $result['data']             = $data;

    $result['responseTime']         = $time_taken;


    echo json_encode($result) . "\n";
//    echo "<pre>";
//    print_r($_SERVER);

}

function oauth_page () {
    header( 'Location: '.$_GET['redirect_uri']. '?code=' . substr(md5(time()), 0, 20) );
}

function produce_token () {

    $r = new stdClass();
    $r->access_token = substr(md5(time()), 0, 20);
    $r->data = array();
    $r->data['@id'] = 'test';
    $r->data['id'] = 1;
    $r->data['name'] = 'test';
    $r->data['passwd'] = 'test';
    $r->expires_in = time();
    $r->refresh_expires = time();
    $r->refresh_token = substr(md5(time()), 0, 20);
    $r->session_id = substr(md5(time()), 0, 20);

    echo json_encode($r);

}

function check_session_id () {
    $r = new stdClass();
    $r->result = 1;
    $r->error = '';

    echo json_encode($r);
}

function logout () {
    echo "session is remove";
}

exit();
