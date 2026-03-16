<?php
include "../scripts/sessionhandler.php";
include "../scripts/databaseConnection.php";

//guard statements
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

$currentUserRole = 0;
if (isset($_SESSION['username'])) {
    $curStmt = $pdo->prepare('SELECT role FROM profiles WHERE username = :username LIMIT 1');
    $curStmt->execute([':username' => $_SESSION['username']]);
    $curRow = $curStmt->fetch(PDO::FETCH_ASSOC);
    if ($curRow) {
        $currentUserRole = (int)$curRow['role'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - <?php echo htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
    <link rel="stylesheet" href="../scripts/scripts.css">
</head>
<!--using data-profile-owner-id instead of id??????-->

<body data-profile-owner-id="<?php echo (int)$user['id']; ?>" data-profile-owner-username="<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>" data-current-user-role="<?php echo (int)$currentUserRole; ?>">
    <?php include "../scripts/nav.php"; ?>

    <div id="pageContentCentering">
        <div id="profileBox">
            <h1>Profile: <?php echo htmlspecialchars($user['username']); ?></h1>

            <div class="profileImageDisplay">
                <img src="../media/upload/<?php echo htmlspecialchars($user['username']); ?>_profile.jpg" alt="Profile Image" onerror="this.onerror=null; this.src='../media/Default.jpg';">
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

            <div id="profileVotesSection">
                <p><strong>Net likes:</strong> <span id="profileLikeCount"><?php echo htmlspecialchars($user['likes'] ?? '0'); ?></span></p>
                <button id="likeBtn" type="button">Like</button>
                <button id="dislikeBtn" type="button">Dislike</button>
                <!--remember to vote kids!-->
                <p id="voteStatus" class="vote-status"></p>
            </div>

            <div class="profileActions">
                <button type="button" onclick="window.history.back();">Back</button>
                <button type="button" onclick="location.href='../homePage/index.php';">Home</button>
            </div>

            <div id="profileCommentsSection">
                <h2>Comments</h2>
                <form id="commentForm">
                    <textarea id="commentText" placeholder="Write your comment..." rows="4" cols="50" required></textarea>
                    <input type="hidden" id="parentCommentId" value="">
                    <button type="submit">Post Comment</button>
                </form>
                <div id="commentsContainer"></div>
            </div>

        </div>
    </div>

    <script src="../scripts/viewuser.js"></script>
</body>

</html>