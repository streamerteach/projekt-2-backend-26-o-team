<?php
include "../scripts/sessionhandler.php";
include "../scripts/databaseConnection.php";

if (!isset($_SESSION['username'])) {
    header('Location: ../login/index.php');
    exit;
}

$requestedUser = $_GET['user'] ?? null;
if (!$requestedUser) {
    http_response_code(400);
    echo "<p>Missing user parameter.</p>";
    exit;
}

$pdo = create_conn();
$stmt = $pdo->prepare('SELECT id, username, realname, zipcode, bio, salary, preference, email, likes, role FROM profiles WHERE username = :user');
$stmt->execute([':user' => $requestedUser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo "<p>User not found.</p>";
    exit;
}

$prefMap = [
    0 => 'All',
    1 => 'Men',
    2 => 'Women',
    3 => 'Other'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
</head>
<body>
    <?php include "../scripts/nav.php"; ?>

    <div id="pageContentCentering">
        <div id="profileBox">
            <h1>Profile: <?php echo htmlspecialchars($user['username']); ?></h1>

            <div class="profileImageDisplay">
                <img src="../media/upload/<?php echo htmlspecialchars($user['username']); ?>_profile.jpg" alt="Profile Image"
                     onerror="this.onerror=null; this.src='../media/Default.jpg';">
            </div>

            <div class="profileInfo">
                <p><strong>Real name:</strong> <?php echo htmlspecialchars($user['realname']); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($user['bio'] ?? 'No bio provided')); ?></p>
                <p><strong>Zip code:</strong> <?php echo htmlspecialchars($user['zipcode'] ?? 'Not specified'); ?></p>
                <p><strong>Salary:</strong> <?php echo htmlspecialchars($user['salary'] ?? 'Not specified'); ?></p>
                <p><strong>Preference:</strong> <?php echo htmlspecialchars($prefMap[$user['preference']] ?? 'Not specified'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? 'Not specified'); ?></p>
                <p><strong>Likes:</strong> <?php echo htmlspecialchars($user['likes'] ?? '0'); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role'] ?? 'Unknown'); ?></p>
            </div>

            <div class="profileActions">
                <button type="button" onclick="window.history.back();">Back</button>
                <button type="button" onclick="location.href='../homePage/index.php';">Home</button>
            </div>
        </div>
    </div>
</body>
</html>
