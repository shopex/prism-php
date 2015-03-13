<?php

//$time_start = microtime(true);
$time_start = $_SERVER['REQUEST_TIME_FLOAT'];
usleep(10*1000);
//sleep(5);
$time_end = microtime(true) - $time_start;
$time_taken = round($time_end, 3)*1000 . "ms";

$result  = array();
parse_str(file_get_contents('php://input'), $data);

$result['httpMethod']           = $_SERVER['REQUEST_METHOD'];

if (@$_SERVER['HTTP_X_API_OAUTH'])
    $result['oauth']            = $_SERVER['HTTP_X_API_OAUTH'];
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
//var_dump($_SERVER);
