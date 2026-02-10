<?php include "../scripts/sessionhandler.php"; ?>
<?php

require '../scripts/sanitize.php';

if (isset($_REQUEST['username']) && isset($_REQUEST['email'])) {
    $username = test_input($_REQUEST['username']);
    $email = trim($_REQUEST['email']);

    //validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Invalid email address!";
        header("Location: ./index.php");
        exit;
    }


    //me when no database :(

    //generate random password
    $password = generateRandomPassword(8);

    //send email with password currently tested but no response on cgi.arcada.
    $to = $email;
    $subject = "Welcome to Unnamed Dating Site - Your Login Credentials";
    $message = "Hello " . htmlspecialchars($username) . ",\n\n";
    $message .= "Your account has been successfully created!\n\n";
    $message .= "Username: " . htmlspecialchars($username) . "\n";
    $message .= "Password: " . $password . "\n\n";
    $message .= "Please log in to your account and change your password.\n\n";
    $message .= "Best regards,\nThe Unnamed Dating Site Team";

    $headers = "From: noreply@Unnameddatingsite.com" . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        // if email sent successfully
        $_SESSION['register_success'] = "Registration successful! A confirmation email with your login credentials has been sent to " . htmlspecialchars($email);
        header("Location: ./index.php");
        exit;
    } else {
        //no email sent
        $_SESSION['register_error'] = "Error sending confirmation email.";
        header("Location: ./index.php");
        exit;
    }
} else {
    //empty fields
    $_SESSION['register_error'] = "Username and email are required!";
    header("Location: ./index.php");
    exit;
}

function generateRandomPassword($length = 8)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

?>

