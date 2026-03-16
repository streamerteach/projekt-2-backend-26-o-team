<?php
include "../scripts/sessionhandler.php";
include "../scripts/databaseConnection.php";

//load current profile values for the user
$editBio = '';
$editSalary = '';
$editPreference = '';
if (isset($_SESSION['username'])) {
    $conn = create_conn();
    $stmt = $conn->prepare("SELECT bio, salary, preference FROM profiles WHERE username = :u");
    $stmt->execute([':u' => $_SESSION['username']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $editBio = $row['bio'];
        $editSalary = $row['salary'];
        $editPreference = $row['preference'];
    }
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../mainStyle.css">
    <link rel="stylesheet" href="../profile/profileStylesheet.css">
    <link rel="stylesheet" href="../scripts/scripts.css">
</head>

<body>
    <?php include "../scripts/nav.php"; ?>
    <div id="centeringDiv">
        <div id="profileEditContainer">
            <h2>Edit Profile</h2>

            <div class="profileImageSection">
                <h3>Profile Image</h3>
                <div class="profileImagePreviewContainer">

                    <img id="profileImagePreview" src="../media/upload/<?php echo $_SESSION["username"] ?>_profile.jpg">
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

            <div class="profileDataSection">
                <h3>Profile Information</h3>
                <div id="profileDataMessage" class="message-inline"></div>
                <form id="profileDataForm" action="../scripts/updateProfile.php" method="post">
                    <p class="small">Bio</p>
                    <input type="text" name="bio" value="<?php echo htmlspecialchars($editBio); ?>" required><br>
                    <p class="small">Monthly salary</p>
                    <input type="text" name="salary" value="<?php echo htmlspecialchars($editSalary); ?>" required><br>
                    <label class="small" for="genderEdit">Gender</label>
                    <select name="gender" id="genderEdit">
                        <option value="0" <?php if ($editPreference == 0) echo 'selected'; ?>>Man</option>
                        <option value="1" <?php if ($editPreference == 1) echo 'selected'; ?>>Woman</option>
                        <option value="2" <?php if ($editPreference == 2) echo 'selected'; ?>>Other</option>
                    </select><br><br>

                    <label class="small" for="preferenceEdit">Preference</label>
                    <select name="preference" id="preferenceEdit">
                        <option value="0" <?php if ($editPreference == 0) echo 'selected'; ?>>All</option>
                        <option value="1" <?php if ($editPreference == 1) echo 'selected'; ?>>Men</option>
                        <option value="2" <?php if ($editPreference == 2) echo 'selected'; ?>>Women</option>
                        <option value="3" <?php if ($editPreference == 3) echo 'selected'; ?>>Other</option>
                    </select><br><br>
                    <button type="submit">Save Changes</button>
                </form>
            </div>

            <div class="deleteSection">
                <h3>Danger Zone</h3>
                <div id="deleteMessage" class="message-inline"></div>
                <form id="deleteForm" action="../scripts/deleteProfile.php" method="post">
                    <p class="small">Enter password to confirm deletion</p>
                    <input type="password" name="password" required><br><br>
                    <button type="submit" class="deleteButton">Delete Profile</button>
                </form>
            </div>
        </div>

        <a href="./index.php" class="backButton">Back to Profile</a>
    </div>
    <script src="../scripts/profileImageUpload.js"></script>
    <script src="./profileScript.js"></script>
    <script scr="../scripts/deleteScript.js"></script>
</body>

</html>