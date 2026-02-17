<header class="header">
    <nav>
        <ul>
            <li class="nav-logo"><a href="../landingPage/index.php"><img src="../media/newlogo_small.png" alt="Logo"></a></li>
            
            <?php
            if (isset($_SESSION["loggedin"])) {
                echo ('<li><a href="../homePage/index.php">Home</a></li>');
                echo ('<li><a href="../profile/index.php">Profile</a></li>');
            } else {
                echo ('<li><a href="../register/index.php">Register</a></li>');
                echo ('<li><a href="../login/index.php">Login</a></li>');
            }
            ?>
            <li><a href="../reportPage/index.php">Report</a></li>
        </ul>
    </nav>
</header>
