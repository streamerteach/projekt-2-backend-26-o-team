<?php ob_start();
include "../scripts/sessionhandler.php";
include "../scripts/nav.php";
include "../scripts/landingPageWelcomeCookies.php";
include "../scripts/visitorCounter.php";
include "../scripts/guestbook.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./landingPageStyle.css">
</head>

<body>
    <div class="hero-main">
        <div class="herotextcontainer">
            <h1>Unnamed Dating Site</h1>
        </div>
    </div>
    <div class="flex-container">
        <div class="flex-info">
            <h2>Find your perfect match, Today!</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            <a href="../login/index.php" class="button-large">Join now!</a>
        </div>
        <div class="flex-image" id="landing-image">
            <p>0 Matches made.. and rising!</p><br> <!-- make dynamic with php after database -->
            <p><?php echo $totalVisitors; ?> Unique Visitors</p>
        </div>
    </div>
    <div class="section-dark">
        <div class="CommentSection">
            <form method="post">
                <div>
                    <input type="text" placeholder="enter name" name="name" required>
                    <br>
                    <textarea name="comment" placeholder="Comment here" rows="4"></textarea>
                    <br>
                </div>
                <input type="submit" value="Comment">
            </form>
            <?php
            if (empty($comments)): ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php else: ?>
                <?php foreach ($comments as $line): ?>
                    <?php
                    [$time, $name, $text] = explode('|', $line, 3);
                    ?>
                    <div class="comment">
                        <div class="meta">
                            <strong><?= htmlspecialchars($name) ?></strong>
                            - <?= htmlspecialchars($time) ?>
                        </div>
                        <div class="text">
                            <?= htmlspecialchars($text) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <img src="../media/newlogo.png" alt="" class="logo">
        <div class="text-section">
            <h2>even more info about this thing</h2>
            <p>With this site you can literally Lorem ipsum dolor sit amet!</p>
        </div>
        <div class="text-section" id="return-message"> <!-- for welcome back message and timestamp -->
            <?php
            echo $message;
            ?>
        </div>
    </div>

    <?php if (!isset($_COOKIE[$consentName])): ?>
        <div id="cookie-banner">
            <form method="post">
                <p>
                    We use cookies to improve your experience.
                    You can choose to accept only necessary cookies or all cookies.
                </p>

                <button type="submit" name="cookie_choice" value="necessary" class="button-large">
                    Accept necessary cookies
                </button>

                <button type="submit" name="cookie_choice" value="all" class="button-large">
                    Accept all cookies
                </button>
            </form>
        </div>
    <?php endif; ?>
</body>

</html>