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

$uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "media" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR;

$filename = $currentUser . "_profile.jpg";
$destination = $uploadDir . $filename;

if (file_exists($destination)) {
    unlink($destination);
} // delete if old image exists. users dont get to store older avatars



// Load and convert image to JPG
$sourceFile = $_FILES["profileImage"]["tmp_name"];
$image = null;

switch ($_FILES["profileImage"]["type"]) { // convert from specific format to jpg to reduce file sizes and make it less complex to display later
    case 'image/png':
        $image = imagecreatefrompng($sourceFile);
        break;
    case 'image/jpeg':
        $image = imagecreatefromjpeg($sourceFile);
        break;
    case 'image/webp':
        $image = imagecreatefromwebp($sourceFile);
        break;
}

if (!$image) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to process image"]);
    exit;
}

$imagequality = rand(1, 100); // yes. this is what dating site users deserve. randomized image quality

$saved = imagejpeg($image, $destination, $imagequality);
imagedestroy($image);

if (!$saved) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to save image"]);
    exit;
}

$relativePath = "../media/upload/" . $filename;
$_SESSION["profileImage"] = $relativePath; // getting rid of profileImage session value soon but whatever

echo json_encode([
    "success" => true,
    "message" => "Image uploaded successfully",
    "filepath" => $relativePath,
    "filename" => $filename
]);
