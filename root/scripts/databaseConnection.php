<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);


function create_conn() {

    $servername = "localhost";
    $username = "kjellmac";
    $password = "mNPHFTrM6g";
    $dbname = "kjellmac";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Could Not Connect. ". $e->getMessage());
    }
}
?>