<?php
include "./databaseConnection.php";
include "./sessionhandler.php";

//get form data
$password = $_POST['password'];
$username = $_POST['username'];
//prepare sql
$sql = "SELECT username, passhash FROM profiles WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = "User Not Found";
    header("Location: ../login/index.php");
    exit;
}

if (password_verify($password, $user['passhash'])) {
    $_SESSION["username"] = $username;
    $_SESSION["loggedin"] = true;
    header("Location: ../profile/index.php");
    exit;
} else {
    $_SESSION['error'] = "Wrong Password";
    header("Location: ../login/index.php");
    exit;
}
