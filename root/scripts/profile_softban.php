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

    $currentUser = $_SESSION['username'];
    $stmt = $pdo->prepare('SELECT role FROM profiles WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $currentUser]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || (int)$row['role'] < 3) {
        http_response_code(403);
        echo json_encode(['error' => 'Insufficient permissions']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Only POST is allowed']);
        exit;
    }

    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JASON']);
        exit;
    }

    $profileId = isset($payload['profile_id']) ? (int)$payload['profile_id'] : null;
    $profileUsername = isset($payload['profile_username']) ? trim($payload['profile_username']) : null;

    if (!$profileId && $profileUsername) {
        $q = $pdo->prepare('SELECT id FROM profiles WHERE username = :username LIMIT 1');
        $q->execute([':username' => $profileUsername]);
        $row = $q->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $profileId = (int)$row['id'];
        }
    }

    if (!$profileId) {
        http_response_code(400);
        echo json_encode(['error' => 'profile_id or profile_username required']);
        exit;
    }

    //prevent self ban. allow moderation on others only
    $userInfo = $pdo->prepare('SELECT username, is_softbanned FROM profiles WHERE id = :id LIMIT 1');
    $userInfo->execute([':id' => $profileId]);
    $targetProfile = $userInfo->fetch(PDO::FETCH_ASSOC);

    if (!$targetProfile) {
        http_response_code(404);
        echo json_encode(['error' => 'Profile not found']);
        exit;
    }

    if ($targetProfile['username'] === $currentUser) {
        http_response_code(403);
        echo json_encode(['error' => 'Cannot softban self']);
        exit;
    }

    $newState = $targetProfile['is_softbanned'] ? 0 : 1;

    $updateStmt = $pdo->prepare('UPDATE profiles SET is_softbanned = :state WHERE id = :id');
    $updateStmt->execute([':state' => $newState, ':id' => $profileId]);

    echo json_encode([
        'message' => $newState ? 'Profile softbanned' : 'Profile unsoftbanned',
        'profile_id' => $profileId,
        'is_softbanned' => (int)$newState,
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Softban action failed', 'details' => $e->getMessage()]);
    exit;
}
