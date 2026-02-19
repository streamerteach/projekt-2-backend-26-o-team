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
                <p class="small">First Name</p>
                <input type="text" name="firstname" required><br>
                <p class="small">Last Name</p>
                <input type="text" name="lastname" required><br>
                <p class="small">ZIP code [ we store your data securely :-) ]</p>
                <input type="text" name="zipcode" required><br>
                <p class="small">Monthly salary</p>
                <input type="text" name="salary" required><br>
                <label class="small" for="preference">Preference</label>
                <select name="preference" id="dropdown">
                  <option value="0">All</option>
                  <option value="1">Men</option>
                  <option value="2">Women</option>
                  <option value="3">Other</option>
                </select>
                <p class="small">e-mail</p>
                <input type="email" name="email" required><br>
                <p class="small">Bio</p>
                <input type="text" name="bio" required><br>
                <p class="small">password</p>
                <input type="password" name="password" required><br>

                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</body>

</html>