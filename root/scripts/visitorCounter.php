<?php
$logFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "visitor_log.txt";

$ip = $_SERVER['REMOTE_ADDR'];
$time = date('d-m-Y H:i:s');

$visitors = [];

//read existing log file
if (file_exists($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        [$loggedIp, $loggedTime] = explode('|', $line);
        $visitors[$loggedIp] = $loggedTime;
    }
}

//if ip not logged log it
if (!isset($visitors[$ip])) {
    file_put_contents(
        $logFile,
        $ip . '|' . $time . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

$totalVisitors = count($visitors);
