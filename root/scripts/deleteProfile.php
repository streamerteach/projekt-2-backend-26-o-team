<?php
//script for deleting profile
include "sessionhandler.php";
include "sanitize.php";
include "databaseConnection.php";

header('Content-Type: application/json; charset=UTF-8');
$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['username'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

if (!isset($_POST['password'])) {
    $response['message'] = 'Password required';
    echo json_encode($response);
    exit;
}

$password = $_POST['password'];

//fetch stored hash for user
$conn = create_conn();
try {
    $stmt = $conn->prepare("SELECT passhash FROM profiles WHERE username = :u");
    $stmt->execute([':u' => $_SESSION['username']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $response['message'] = 'User record not found';
        echo json_encode($response);
        exit;
    }
    if (!password_verify($password, $row['passhash'])) {
        $response['message'] = 'Incorrect password';
        echo json_encode($response);
        exit;
    }

    //delete user
    $del = $conn->prepare("DELETE FROM profiles WHERE username = :u");
    $del->execute([':u' => $_SESSION['username']]);
    session_unset();
    session_destroy();
    $response['success'] = true;
    $response['message'] = 'Profile deleted. You will be logged out.';
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}
$conn = null;

echo json_encode($response);
exit;
