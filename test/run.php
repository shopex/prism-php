<?php

// 需要PHP > 5
// 清空report
file_put_contents('report', '');


// 开启服务器
$descriptorspec = array(
   0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
   1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
//   2 => array("file", "report", "a") // 标准错误，写入到一个文件
   2 => array("pipe", "r") // 标准错误，写入到一个文件
);

$server = proc_open('php -S 0.0.0.0:8080 test-server.php', $descriptorspec, $pipes);


// 测试
popen('phpunit CurlTest.php >> report', 'r');
popen('phpunit SocketTest.php >> report', 'r');
popen('phpunit PrismSignTest.php >> report', 'r');
popen('phpunit OauthTest.php >> report', 'r');
popen('phpunit NotifyTest.php >> report', 'r');


// 分析report
$report = file_get_contents(__DIR__.'/report');

preg_match_all('/OK/', $report, $r[]);
preg_match_all('/FAILURES/', $report, $r[]);
preg_match_all('/Exception[^\n]+/', $report, $r[]);

foreach ($r as $value) {
    echo implode("\n", array_shift($value)) . "\n";
}

proc_terminate($server);
