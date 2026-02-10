<?php 
include "../scripts/sessionhandler.php";
include "../scripts/databaseConnection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiles</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./viewProfiles.css">
</head>
<body>
        <?php include "../scripts/nav.php"; ?>

    <?php include "view_profiles.php" ?>
</body>
</html>