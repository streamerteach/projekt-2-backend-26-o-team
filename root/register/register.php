<?php include "../scripts/sessionhandler.php"; ?>
<?php
// why put the only other include outside php tags? (⌐■_■) because i can
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

    $to = $email;

    $subject = "Welcome to Unnamed Dating Site - Your Login Credentials";

    $message =
        "Hello $username\n\n" .
        "Your account has been successfully created!\n\n" .
        "Username: $username\n" .
        "Password: $password\n\n" .
        "Please log in and change your password.\n";

    $from = "kjellmac@arcada.fi"; //hohohoh

    $headers  = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $sent = mail($to, $subject, $message, $headers, "-f$from");


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

