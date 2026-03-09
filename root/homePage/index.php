<?php include "../scripts/sessionhandler.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./homePageStyle.css">
</head>

<body>
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
<script src="./profile_loader.js"></script>
<script type="module" src="../mainScript.js"></script>

</html>