<?php
include "../scripts/databaseConnection.php";

ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


function create_user(
$username,
$realname,
$zipcode,
$bio,
$salary,
$preference,
$email,
$role,
$password) {

    
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
} catch(PDOException) {
    echo("failed");
}
}

?>