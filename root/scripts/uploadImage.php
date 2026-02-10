<?php
session_start();
//check if POST
ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header('Content-Type: application/json');

//use http encode instead of exit()
/* error codes
400 - bad request
401 - unauthorized
405 - method not allowed post required 
500 - server error
*/


include "imageHelper.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "POST Method required"]);
    exit;
}

if ($_FILES["profileImage"]["error"] !== UPLOAD_ERR_OK) {
    $error_message = "Unknown upload error";
    switch ($_FILES["profileImage"]["error"]) {
        case UPLOAD_ERR_PARTIAL:
            $error_message = "Error: partially uploaded file";
            break;
        case UPLOAD_ERR_NO_FILE:
            $error_message = "Error: No file uploaded";
            break;
        case UPLOAD_ERR_EXTENSION:
            $error_message = "Error: File upload stopped due to PHP extension";
            break;
        //image too large milord, thy painting must be less than 5MB
        case UPLOAD_ERR_FORM_SIZE:
            $error_message = "Error: Uploaded file too large (5MB or smaller only)";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $error_message = "Error: Failed to write file";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $error_message = "Error: Temp folder not found";
            break;
    }
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $error_message]);
    exit;
}

if ($_FILES["profileImage"]["size"] > 5000000) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "File too large (Max 5MB)"]);
    exit;
}

$mime_types = ["image/png", "image/jpeg", "image/webp"];
if (!in_array($_FILES["profileImage"]["type"], $mime_types)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid file type (jpeg,png,webp only)"]);
    exit;
}
//return mime type ex: image/png
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->file($_FILES["profileImage"]["tmp_name"]);

//sanitize
$pathinfo = pathinfo($_FILES["profileImage"]["name"]);
$base = $pathinfo["filename"];
$base = preg_replace("/[^\w-]/", "_", $base);
$currentUser = $_SESSION["username"];

if (empty($currentUser)) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "User not logged in"]); //L
    exit;
}

//numbers and letters only
if (!preg_match("/^[a-zA-Z0-9_-]+$/", $currentUser)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid username format"]);
    exit;
}

$userFolder = dirname(__DIR__) . DIRECTORY_SEPARATOR . "media" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $currentUser;

//check if user folder exists, if not create it
if (!is_dir($userFolder)) {
    if (!mkdir($userFolder, 0755, false)) { // nonrecursive cause you're fucked if everything else doesn't exist
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to create user folder"]);
        exit;
    };
}

$filename = $base . "." . $pathinfo["extension"];
$destination = $userFolder . DIRECTORY_SEPARATOR . $filename;

$i = 1;

while (file_exists($destination)) {
    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = $userFolder . DIRECTORY_SEPARATOR . $filename;

    $i++;
}
if (!move_uploaded_file($_FILES["profileImage"]["tmp_name"], $destination)) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Can't move uploaded file"]);
    exit;
}

$relativePath = "../media/upload/" . $currentUser . "/" . $filename;
$_SESSION["profileImage"] = $relativePath;

//latest
$latestImage = getLatestProfileImage($userFolder);
$latestImagePath = $latestImage
    ? "../media/upload/" . $currentUser . "/" . $latestImage
    : null;

//second latest
$secondImage = getUserSecondImagePath($userFolder);
$secondImagePath = $secondImage
    ? "../media/upload/" . $currentUser . "/" . $secondImage
    : null;


echo json_encode([
    "success" => true,
    "message" => "Image uploaded successfully",
    "filepath" => $relativePath,
    "latestImage" => $latestImagePath,
    "secondLatestImage" => $secondImagePath

]);
