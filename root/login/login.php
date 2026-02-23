<?php include "../scripts/sessionhandler.php"; ?>
<?php include "../scripts/databaseConnection.php"; ?>
<?php


require '../scripts/sanitize.php';
if (isset($_REQUEST['username']) or isset($_REQUEST['password'])) {
    $conn = create_conn();
    //sanitize inputs
    $username = test_input($_REQUEST['username']);
    $password = test_input($_REQUEST['password']);

    $conn = create_conn();
    $user = get_user($username, $conn);
    $conn = null; // yet again a young $conn dies so early.. tragic, really.
    if ($user && verify_user($user,$password)) { // get_user will return false if the user isnt found
        populate_session($user);
        header("Location: ../profile/index.php");
        exit;
    } else {
        login_error();
    }
}

function get_user($username, $conn) {
    $sql = "SELECT * FROM profiles WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);
    return $stmt->fetch();
}

function verify_user($user,$password) {
    if (password_verify($password,$user['passhash'])) {
        return true;
    } else {
        return false;
    }
}

function populate_session($user) { // ONLY EVER RUN AFTER VERIFYING PASSWORD!
        $_SESSION["username"] = $user['username'];
        $_SESSION["realname"] = $user['realname'];
        $_SESSION["bio"] = $user['bio'];
        $_SESSION["role"] = $user['role'];
        $_SESSION["loggedin"] = true;
}

function login_error() {
        $_SESSION["login_error"] = "Incorrect username/password!";
        header("Location: ./index.php");
        exit;
}
?>

