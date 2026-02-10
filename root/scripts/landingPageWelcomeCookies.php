<?php
$consentName = "cookie_consent";

// check form submission
if (isset($_POST["cookie_choice"])) {
    $choice = $_POST["cookie_choice"];
    setcookie($consentName, $choice, time() + (30 * 24 * 60 * 60), "/"); // 30 days
    $_COOKIE[$consentName] = $choice;
} elseif (isset($_COOKIE[$consentName])) {
    // Use existing consent
    $choice = $_COOKIE[$consentName];
} else {
    // No consent
    $choice = null;
}

$cookieName = "last_visit";
$now = time();

// only show welcome back message and set tracking cookie if user accepted all cookies
if ($choice == "all") {
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
        // first visit after accepting all cookies
        $message = "Welcome! To Unnamed Dating Site";
    }
    // set the tracking cookie
    setcookie($cookieName, $now, time() + (30 * 24 * 60 * 60), "/");
} else {
    $message = "Welcome! To Unnamed Dating Site";
}
