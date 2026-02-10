<?php include "../scripts/sessionhandler.php"; ?>
<?php include "../scripts/nav.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./loginStylesheet.css">

</head>

<body>
    <div id="centeringDiv">
        <div id="loginForm">
            <?php
            if (isset($_SESSION['login_error'])) {
                echo "<div style='color: red; margin-bottom: 15px;'>" . htmlspecialchars($_SESSION['login_error']) . "</div>";
                unset($_SESSION['login_error']);
            }
            ?>
            <form action="./login.php" method="post">
                <p class="small">username</p>
                <input type="text" name="username"><br>
                <p class="small">password</p>
                <input type="password" name="password"><br><br>
                <a href="http://www.crouton.net">Forgot Password?</a><br><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>

</html>