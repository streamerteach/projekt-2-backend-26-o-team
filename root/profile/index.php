<?php
/*
if (isset($_SESSION["loggedin"])) {
    include "../scripts/sessionhandler.php";
} else {
    header("HTTP/1.1 401 Unauthorized");
    print_r($_SESSION);
    echo "You must be logged in to access this page.";
    exit;
}
*/
//for debug and dev only
include "../scripts/sessionhandler.php";
include "../scripts/timeToDate.php";
include "../scripts/databaseConnection.php";

//fetch current user details for display
$userDetails = [];
if (isset($_SESSION['username'])) {
    $conn = create_conn();
$stmt = $conn->prepare("SELECT id, realname, bio, salary, preference, gender, likes, role FROM profiles WHERE username = :u");
    $stmt->execute([':u' => $_SESSION['username']]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
    <link rel="stylesheet" href="../scripts/scripts.css">
</head>

<body data-profile-owner-id="<?php echo (int)($userDetails['id'] ?? 0); ?>" data-current-user-role="<?php echo (int)($userDetails['role'] ?? 0); ?>">
    <?php include "../scripts/nav.php"; ?>
    <div id="pageContentCentering">
        <div id="profileBox">

            <?php
            if (isset($_SESSION["username"]))
                print("<div id='user'>Logged in as: <span class='bold-text'>" . $_SESSION["username"] . "</span></div>")
            ?>

                <div class="profileImageDisplay">
                    <img src="../media/upload/<?php echo $_SESSION["username"] ?>_profile.jpg" alt="Profile Image">
                </div>


            <?php if (!empty($userDetails)): ?>
                <div class="profileInfo">
                    <p><strong>Real name:</strong> <?php echo htmlspecialchars($userDetails['realname']); ?></p>
                    <p><strong>Gender:</strong> <?php 
                    $mapgender = ['0'=> 'Man', '1'=>'Woman', '2'=>'Other'];
                    echo $mapgender[$userDetails['gender']]; ?></p>
                    <p><strong>Bio:</strong> <?php echo htmlspecialchars($userDetails['bio']); ?></p>
                    <p><strong>Salary:</strong> <?php echo htmlspecialchars($userDetails['salary']); ?></p>
                    <p><strong>Preference:</strong> <?php
                        $map = ['0'=> 'All', '1'=>'Men', '2'=>'Women', '3'=>'Other'];
                        echo $map[$userDetails['preference']] ?? 'Unknown';
                    ?></p>
                    <p><strong>Likes:</strong> <span id="profileLikeCount"><?php echo htmlspecialchars($userDetails['likes'] ?? '0'); ?></span></p>
                    <div id="profileVoteArea">
                        <button id="profileLikeBtn" type="button">Like</button>
                        <button id="profileDislikeBtn" type="button">Dislike</button>
                        <p id="profileVoteStatus" class="vote-status">You have not voted yet.</p>
                    </div>
                </div>
            <?php endif; ?>

            <div>
                <button type="button" onclick="location.href='../profile/editProfile.php'">Edit Profile</button>
                <button type="button" onclick="location.href='../scripts/logout.php'">Logout</button>
                <button onclick="location.href='../scripts/cube.php'">Cube</button>
            </div>

            <div id="profileCommentsSection">
                <h2>Your Profile Comments</h2>
                <form id="profileCommentForm">
                    <textarea id="profileCommentText" placeholder="Write a comment on your profile" rows="4" cols="50" required></textarea>
                    <input type="hidden" id="profileParentCommentId" value="">
                    <button type="submit">Post Comment</button>
                </form>

                <div id="profileCommentsContainer"></div>
            </div>

        </div>
        <div id="dateTimeBox">
            <form action="../scripts/timeToDate.php" method="get">
                <label for="dateTimeInput">Calculate hours till your date. Input your meeting time</label><br><br>
                <input type="datetime-local" id="dateTimeInput" name="dateTimeInput">
                <input type="submit" value="Calculate">
            </form>
            <div id="output">
            </div>
        </div>

        <script src="../scripts/timeDifference.js"></script>
        <script src="../scripts/profileSelf.js"></script>
</body>

</html>