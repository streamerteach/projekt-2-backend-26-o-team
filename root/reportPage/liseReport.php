<?php include "../scripts/sessionhandler.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lise Report</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="./reportStyle.css">

</head>

<body>
    <?php include "../scripts/nav.php"; ?>
    <div class=bg>
        <h2 class=c1>Project report // Lise</h2>
        <p class=report-text>This part of the project involved creating a dynamic PHP-based website. We chose to make a dating site, with basic login and account functionality. The current website layout contains a landing, login, register, profile, and home page.</p>
        <h3 class=c2>Design</h3>
        <p class=report-text> The landing page was designed to imitate a slightly modern but still intimate/casual style, to fit with the dating theme. The various shades of purple to dark blue also help set the general vibes. </p>
        <p class=report-text> Other pages than the landing page haven't gotten the same design treatment yet, but after all database and PHP functionality is completed they will match the theme the landing page has set. </p>
        <h3 class=c3>PHP details</h3>
        <p class=report-text>Erik did most of the PHP, while i did fixes and basic scripts. I plan on writing a larger part of the code in the second part of the project. </p>
        <p class=report-text>Working with php so far has been slightly frustrating due to its bad error reporting.</p>
        <h3 class=c4>Project Issues</h3>
        <p class=report-text>There were some issues with folder permissions which kept us from finishing the profile picture and guestbook functionality, but it was fixed.</p>
        <p class=report-text>Since the five server plugin had some issues and did not have the same php configuration settings as the CGI server, it was a bit difficult to work on locally. Because of this, i decided to create a github action which pushed all new changes directly to the CGI server, letting me see the live production environment version quicker. For the second part of the project, i will set up a working local environment with a database set up in the same way as the cgi database to let me work faster and more efficiently.
        </p>        

    </div>
</body>

</html>
