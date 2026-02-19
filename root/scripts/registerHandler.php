<?php
include "./databaseConnection.php";
include "./sessionhandler.php";

ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$username = "alfred";
$realname = "alfred krupp";
$zipcode = 04250;
$bio = "bowow";
$salary = 20000;
$preference = 1;
$email = "alfred@gmail.com";
$role = 1;
$password = "password";

$passhash = password_hash($password, PASSWORD_DEFAULT);
try{
    $sql = "INSERT INTO profiles 
(username, realname, zipcode, bio, salary, preference, email, likes, role, passhash)
VALUES 
(:username, :realname, :zipcode, :bio, :salary, :preference, :email, NULL, 1, :passhash)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':username' => $username,
    ':realname' => $realname,
    ':zipcode' => $zipcode,
    ':bio' => $bio,
    ':salary' => $salary,
    ':preference' => $preference,
    ':email' => $email,
    ':passhash' => $passhash
]);
echo ("user created");
} catch (e) {
    echo("failed");
}

?>