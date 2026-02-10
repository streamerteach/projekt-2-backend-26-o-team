<?php include "../scripts/sessionhandler.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./registerStylesheet.css">
</head>

<body>
    <?php include "../scripts/nav.php"; ?>
    <div id="centeringDiv">
        <div id="registerBox">
            <h2>Create Your Account</h2>
            <?php
            if (isset($_SESSION['register_success'])) {
                echo "<div style='color: green; margin-bottom: 15px; padding: 10px; border: 1px solid green; border-radius: 4px;'>" . htmlspecialchars($_SESSION['register_success']) . "</div>";
                unset($_SESSION['register_success']);
            }
            if (isset($_SESSION['register_error'])) {
                echo "<div style='color: red; margin-bottom: 15px; padding: 10px; border: 1px solid red; border-radius: 4px;'>" . htmlspecialchars($_SESSION['register_error']) . "</div>";
                unset($_SESSION['register_error']);
            }
            ?>
            <form action="./register.php" method="post">
                <p class="small">username</p>
                <input type="text" name="username" required><br>
                <p class="small">e-mail</p>
                <input type="email" name="email" required><br>
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</body>

</html>