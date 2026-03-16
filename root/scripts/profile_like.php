<?php
header('Content-Type: application/json; charset=utf-8');
include "databaseConnection.php";
include "sessionhandler.php";

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

try {
    $pdo = create_conn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //current user id
    $currentUsername = $_SESSION['username'];
    $userStmt = $pdo->prepare('SELECT id FROM profiles WHERE username = :username LIMIT 1');
    $userStmt->execute([':username' => $currentUsername]);
    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);
    if (!$userRow) {
        http_response_code(403);
        echo json_encode(['error' => 'Logged-in user profile not found']);
        exit;
    }
    $userId = (int)$userRow['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $profileOwnerId = isset($_GET['profile_owner_id']) ? (int)$_GET['profile_owner_id'] : null;
        if (!$profileOwnerId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing profile_owner_id']);
            exit;
        }

        $stmt = $pdo->prepare('SELECT likes FROM profiles WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $profileOwnerId]);
        $likes = (int)$stmt->fetchColumn();

        echo json_encode(['likes' => $likes, 'user_vote' => 0]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JASON']);
            exit;
        }

        $profileOwnerId = isset($data['profile_owner_id']) ? (int)$data['profile_owner_id'] : null;
        $profileOwnerUsername = isset($data['profile_owner_username']) ? trim($data['profile_owner_username']) : null;
        $action = isset($data['action']) ? strtolower(trim($data['action'])) : null;

        if (!$profileOwnerId && $profileOwnerUsername) {
            $ownerStmt = $pdo->prepare('SELECT id FROM profiles WHERE username = :username LIMIT 1');
            $ownerStmt->execute([':username' => $profileOwnerUsername]);
            $ownerRow = $ownerStmt->fetch(PDO::FETCH_ASSOC);
            if ($ownerRow) {
                $profileOwnerId = (int)$ownerRow['id'];
            }
        }

        if (!$profileOwnerId || !in_array($action, ['like', 'dislike', 'clear'], true)) {
            http_response_code(400);
            echo json_encode(['error' => 'profile_owner_id and valid action are required']);
            exit;
        }

        if ($profileOwnerId === $userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Cannot like/dislike your own profile']);
            exit;
        }

        $value = 0;
        if ($action === 'like') $value = 1;
        if ($action === 'dislike') $value = -1;

        if ($action === 'clear') {
            //leftover
        } else {
            if ($action === 'like') {
                $stmt = $pdo->prepare('UPDATE profiles SET likes = likes + 1 WHERE id = :id');
                $stmt->execute([':id' => $profileOwnerId]);
            } elseif ($action === 'dislike') {
                $stmt = $pdo->prepare('UPDATE profiles SET likes = likes - 1 WHERE id = :id');
                $stmt->execute([':id' => $profileOwnerId]);
            }
        }

        //read profile likes
        $likeCountStmt = $pdo->prepare('SELECT likes FROM profiles WHERE id = :id');
        $likeCountStmt->execute([':id' => $profileOwnerId]);
        $netLikes = (int)$likeCountStmt->fetchColumn();

        echo json_encode(['likes' => $netLikes, 'user_vote' => $action === 'clear' ? 0 : $value]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Profile like error', 'details' => $e->getMessage()]);
    exit;
}
