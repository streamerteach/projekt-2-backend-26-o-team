<?php include "../scripts/sessionhandler.php"; ?>
<?php
// why put the only other include outside php tags? (⌐■_■) because i can
require '../scripts/sanitize.php';
require '../scripts/registerHandler.php';
if (isset($_REQUEST['username']) && isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
    $username = test_input($_REQUEST['username']); // unique check TODO
    $password = test_input($_REQUEST['password']);
    $firstname = test_input($_REQUEST['firstname']);
    $lastname = test_input($_REQUEST['lastname']);
    $realname = trim($firstname) + " " + trim($lastname);
    $zipcode = test_input($_REQUEST['zipcode']); // numbers only check TODO
    $salary = test_input($_REQUEST['salary']); // numbers only check TODO
    $preference = test_input($_REQUEST['preference']); // 0123 only check TODO
    $email = trim(test_input($_REQUEST['email'])); // unique check TODO
    $bio = test_input($_REQUEST['bio']);



print($username);
print($password);
print($realname);
print($zipcode);
print($salary);
print($preference);
print($email);
print($bio);
    /*validate email format 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Invalid email address!";
        header("Location: ./index.php");
        exit;
    }*/

create_user($username,$realname,$zipcode,$bio,$salary,$preference,$email,$role,$password);
    
} else {
    //empty fields
    $_SESSION['register_error'] = "Username, Password and email are required!";
    header("Location: ./index.php");
    exit;
}




?>



//old code for sending mail with random password
//generate random password
    /*
function generateRandomPassword($length = 8)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}



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
    */