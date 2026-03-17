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

        $stmt = $pdo->prepare('SELECT COALESCE(likes, 0) AS likes FROM profiles WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $profileOwnerId]);
        $likes = (int)$stmt->fetchColumn();

        $userVote = 0;
        if (isset($_SESSION['profile_votes']) && isset($_SESSION['profile_votes'][$profileOwnerId])) {
            $userVote = (int)$_SESSION['profile_votes'][$profileOwnerId];
        }

        echo json_encode(['likes' => $likes, 'user_vote' => $userVote]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
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

        if (!isset($_SESSION['profile_votes'])) {
            $_SESSION['profile_votes'] = [];
        }

        $currentVote = $_SESSION['profile_votes'][$profileOwnerId] ?? 0;
        $newVote = $currentVote;
        $delta = 0;

        if ($action === 'like') {
            if ($currentVote === -1) {
                $delta = 2;
            } elseif ($currentVote === 0) {
                $delta = 1;
            }
            $newVote = 1;
        } elseif ($action === 'dislike') {
            if ($currentVote === 1) {
                $delta = -2;
            } elseif ($currentVote === 0) {
                $delta = -1;
            }
            $newVote = -1;
        } elseif ($action === 'clear') {
            if ($currentVote === 1) {
                $delta = -1;
            } elseif ($currentVote === -1) {
                $delta = 1;
            }
            $newVote = 0;
        }

        if ($delta !== 0) {
            $stmt = $pdo->prepare('UPDATE profiles SET likes = COALESCE(likes, 0) + :delta WHERE id = :id');
            $stmt->execute([':delta' => $delta, ':id' => $profileOwnerId]);
        }

        $_SESSION['profile_votes'][$profileOwnerId] = $newVote;

        //read profile likes
        $likeCountStmt = $pdo->prepare('SELECT COALESCE(likes, 0) AS likes FROM profiles WHERE id = :id');
        $likeCountStmt->execute([':id' => $profileOwnerId]);
        $netLikes = (int)$likeCountStmt->fetchColumn();

        echo json_encode(['likes' => $netLikes, 'user_vote' => $newVote]);
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
