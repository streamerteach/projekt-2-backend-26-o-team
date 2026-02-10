<?php
//local to utc time conversion. i am laughing and jumping with joy. :(
//before time was always off by 1 hour
if (isset($_GET['dateTimeInput'])) {
    $dateTimeInput = $_GET['dateTimeInput'];
    $tzOffset = isset($_GET['tzOffset']) ? (int)$_GET['tzOffset'] : 0;

    // create datetime as UTC
    $dateTime = new DateTime($dateTimeInput, new DateTimeZone('UTC'));

    // utc to local time 
    $dateTime->modify($tzOffset . ' minutes');

    // return jason with timestamp
    header('Content-Type: application/json');
    echo json_encode([
        'timestamp' => $dateTime->getTimestamp() * 1000, // convert to millisecond
        'dateTimeInput' => $dateTimeInput
    ]);
    exit;
}
