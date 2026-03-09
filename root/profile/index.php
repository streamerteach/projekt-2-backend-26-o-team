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
    $stmt = $conn->prepare("SELECT realname, bio, salary, preference, likes FROM profiles WHERE username = :u");
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
</head>

<body>
    <?php include "../scripts/nav.php"; ?>
    <div id="pageContentCentering">
        <div id="profileBox">

            <?php
            if (isset($_SESSION["username"]))
                print("<div id='user'>Logged in as: <bold style='font-weight:bold;'>" . $_SESSION["username"] . "<bold></div>")
            ?>

                <div class="profileImageDisplay">
                    <img src="../media/upload/<?php echo $_SESSION["username"] ?>_profile.jpg" alt="Profile Image">
                </div>


            <?php if (!empty($userDetails)): ?>
                <div class="profileInfo">
                    <p><strong>Real name:</strong> <?php echo htmlspecialchars($userDetails['realname']); ?></p>
                    <p><strong>Bio:</strong> <?php echo htmlspecialchars($userDetails['bio']); ?></p>
                    <p><strong>Salary:</strong> <?php echo htmlspecialchars($userDetails['salary']); ?></p>
                    <p><strong>Preference:</strong> <?php
                        $map = ['0'=> 'All', '1'=>'Men', '2'=>'Women', '3'=>'Other'];
                        echo $map[$userDetails['preference']] ?? 'Unknown';
                    ?></p>
                    <p><strong>Likes:</strong> <?php echo htmlspecialchars($userDetails['likes'] ?? '0'); ?></p>
                </div>
            <?php endif; ?>

            <div>
                <button type="button" onclick="location.href='../profile/editProfile.php'">Edit Profile</button>
                <button type="button" onclick="location.href='../scripts/logout.php'">Logout</button>
                <button onclick="location.href='../scripts/cube.php'">Cube</button>
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
</body>

</html>