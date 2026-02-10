<?php
$consentName = "cookie_consent";
$cookieName = "last_visit";
$now = time();

// post-Redirect-Get POST then redirect to avoid resubmission. prevent strange cookie banner behaviour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cookie_choice'])) {
    $choice = $_POST['cookie_choice'];

        setcookie($consentName, $choice, time() + (30 * 24 * 60 * 60), '/'); // 30 days

    // redirect back to the same URL so subsequent reloads wont resubmit the form
    $redirectUrl = $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirectUrl, true, 303);
    exit;
}

if (isset($_COOKIE[$consentName])) {
    $choice = $_COOKIE[$consentName];
} else {
    $choice = null;
}

// only show welcome back message and set tracking cookie if user accepted all cookies
if ($choice === 'all') {
    if (isset($_COOKIE[$cookieName])) {
        // return visitor
        $lastVisit = $_COOKIE[$cookieName];
        $message = "Welcome back to Unnamed Dating Site! <br> Your last visit was on " . date("d-m-Y H:i:s", $lastVisit);
        if (isset($_SESSION['username'])) {
            $message .= "<br>Logged in as: " . ($_SESSION['username']);
        } else {
            $message .= "<br>You are not logged in.";
        }
    } else {
        $message = "Welcome! To Unnamed Dating Site";
    }

    // set/update the tracking cookie now that consent exists
    setcookie($cookieName, $now, time() + (30 * 24 * 60 * 60), '/');
} else {
    $message = "Welcome! To Unnamed Dating Site";
}
