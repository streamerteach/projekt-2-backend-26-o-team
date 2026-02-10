<?php include "../scripts/sessionhandler.php"; ?>
<?php

require '../scripts/sanitize.php';
if (isset($_REQUEST['username']) or isset($_REQUEST['password'])) {

    //sanitize inputs
    $username = test_input($_REQUEST['username']);
    $password = test_input($_REQUEST['password']);

    //hardcoded login for demo purposes. register currently does not store passwords or usernames or anything just sends an email with random password
    if ($username == "admin" && $password == "password") {
        $_SESSION["username"] = $username;
        $_SESSION["loggedin"] = true;
        header("Location: ../profile/index.php");
        exit;
    } else {
        $_SESSION["login_error"] = "Incorrect username/password!";
        header("Location: ./index.php");
        exit;
    }
}
?>
