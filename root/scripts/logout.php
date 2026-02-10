<?php
session_start();
//if use only session destroy cookie persists. dont know why
// clear the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// destroy! destroy the child!!!
session_destroy();

header("Location: ../landingPage/index.php");
exit;
