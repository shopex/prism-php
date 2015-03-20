<?php

file_put_contents('report', '');

popen('phpunit CurlTest.php >> report', 'r');
popen('phpunit SocketTest.php >> report', 'r');
popen('phpunit SignTest.php >> report', 'r');
popen('phpunit OauthTest.php >> report', 'r');


$report = file_get_contents('report');


preg_match_all('/OK/', $report, $r[]);
preg_match_all('/FAILURES/', $report, $r[]);
preg_match_all('/Exception.*\r/', $report, $r[]);

foreach ($r as $value) {
    echo implode("\n", array_shift($value)) . "\n";
}
