<?php
include "../scripts/sessionhandler.php";
require '../scripts/sanitize.php';
require '../scripts/registerHandler.php';
include "../scripts/databaseConnection.php";

//always return jason
header('Content-Type: application/json; charset=UTF-8');
$response = ['success' => false, 'message' => ''];

if (isset($_REQUEST['username']) && isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
    $username = test_input($_REQUEST['username']);
    $password = test_input($_REQUEST['password']);
    $firstname = test_input($_REQUEST['firstname']);
    $lastname = test_input($_REQUEST['lastname']);
    $gender = test_input($_REQUEST['gender']);
    $realname = trim($firstname) . " " . trim($lastname);
    $zipcode = test_input($_REQUEST['zipcode']);
    $salary = test_input($_REQUEST['salary']);
    $preference = test_input($_REQUEST['preference']);
    $email = trim(test_input($_REQUEST['email']));
    $bio = test_input($_REQUEST['bio']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email address!";
        echo json_encode($response);
        exit;
    }

    $conn = create_conn();
    $result = create_user($username, $realname, $zipcode, $bio, $salary, $preference, $gender, $email, $password, $conn);
    $conn = null;

    if ($result['success']) {
        $response['success'] = true;
        $response['message'] = "Registration successful!";
    } else {
        $response['message'] = "Registration failed: " . $result['message'];
    }
    echo json_encode($response);
    exit;
} else {
    $response['message'] = "Username, Password and email are required!";
    echo json_encode($response);
    exit;
}
