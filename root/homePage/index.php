<?php
include "../scripts/sessionhandler.php";
include "../scripts/databaseConnection.php";

$userRole = 0;
if (isset($_SESSION['username'])) {
    $conn = create_conn();
    $stmt = $conn->prepare('SELECT role FROM profiles WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $_SESSION['username']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $userRole = (int) $row['role'];
    }
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./homePageStyle.css">
</head>

<body data-user-role="<?php echo (int)$userRole; ?>">
    <?php include "../scripts/nav.php"; ?>
    <div id="centeringDiv">
        <h2>Welcome to the Home Page</h2>
        

        <div id="datingProfilesContainer">
            
        </div>
        <h3 id="loadingIndicator" class="loading-indicator">Loading</h3>
        <h3 id="endMessage">there's no more! :-)</h3>
        <h3 id="errorMessage">errorrr</h3>
    </div>
</body>
<script src="./setUserRole.js"></script>
<script src="./profile_loader.js"></script>
<script type="module" src="../mainScript.js"></script>

</html>