<?php
//script for updating bio, salary, preference
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

if (isset($_POST['bio']) && isset($_POST['salary']) && isset($_POST['preference'])) {
    $bio = test_input($_POST['bio']);
    $salary = test_input($_POST['salary']);
    $preference = test_input($_POST['preference']);

    if (!is_numeric($salary) || $salary < 0) {
        $response['message'] = 'Salary must be a non‑negative number';
        echo json_encode($response);
        exit;
    }
    if (!in_array($preference, ['0', '1', '2', '3'])) {
        $response['message'] = 'Invalid preference value';
        echo json_encode($response);
        exit;
    }

    $conn = create_conn();
    try {
        $stmt = $conn->prepare("UPDATE profiles SET bio = :bio, salary = :salary, preference = :preference WHERE username = :username");
        $stmt->execute([
            ':bio' => $bio,
            ':salary' => $salary,
            ':preference' => $preference,
            ':username' => $_SESSION['username']
        ]);
        $response['success'] = true;
        $response['message'] = 'Profile updated successfully';
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
    $conn = null;
} else {
    $response['message'] = 'Missing profile fields';
}

echo json_encode($response);
exit;
