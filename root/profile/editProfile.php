<?php
include "../scripts/sessionhandler.php";
include "../scripts/imageHelper.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
</head>

<body>
    <?php include "../scripts/nav.php"; ?>
    <div id="centeringDiv">
        <div id="profileEditContainer">
            <h2>Edit Profile</h2>

            <div class="profileImageSection">
                <h3>Profile Image</h3>
                <div class="profileImagePreviewContainer">
                    <?php
                    // get latest image from folder first fallback to session
                    $profileImage = getUserImagePath($_SESSION["username"] ?? null);
                    if (!$profileImage && isset($_SESSION["profileImage"])) {
                        $profileImage = $_SESSION["profileImage"];
                    }
                    $secondProfileImage = getUserSecondImagePath($_SESSION["username"] ?? null);
                    if (!$secondProfileImage && isset($_SESSION["secondProfileImage"])) {
                        $secondProfileImage = $_SESSION["secondProfileImage"];
                    }

                    if ($profileImage): ?>
                        <img id="profileImagePreview" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
                    <?php endif; ?>
                    <?php
                    if ($secondProfileImage): //gross php slop
                    ?>
                        <img id="oldProfileImagePreview" src="<?php echo htmlspecialchars($secondProfileImage); ?>" alt="Second Latest Image">
                    <?php endif; ?>
                </div>

                <div class="imageUploadForm">
                    <form id="uploadForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
                        <label for="profileImageFile">Profile Image</label>
                        <input type="file" id="profileImageFile" name="profileImage" accept="image/jpeg,image/png,image/webp" required> <!--I know project description states only to accept jpeg and png but webp is very common-->
                        <button type="submit">Upload Image</button>
                    </form>
                    <div id="uploadMessage" class="uploadMessage"></div>
                </div>
            </div>
        </div>

        <a href="./index.php" class="backButton">Back to Profile</a>
    </div>
    <script src="../scripts/profileImageUpload.js"></script>
</body>

</html>
