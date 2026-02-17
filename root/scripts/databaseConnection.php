<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "kjellmac";
$password = "mNPHFTrM6g";
$dbname = "kjellmac";

try {
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could Not Connect. ". $e->getMessage());
}
?>