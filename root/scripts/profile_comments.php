<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');
include "databaseConnection.php";
include "sessionhandler.php";

$pdo = create_conn();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['parent_comment_id'])) {
            $parentId = (int)$_GET['parent_comment_id'];
            $stmt = $pdo->prepare('SELECT c.id, c.user_id, c.profile_owner_id, c.parent_comment_id, c.content, c.created_at, c.updated_at, u.username AS author_username FROM profile_comments c LEFT JOIN profiles u ON u.id = c.user_id WHERE c.parent_comment_id = :parent AND c.is_deleted = FALSE ORDER BY c.created_at ASC');
            $stmt->execute([':parent' => $parentId]);
            $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['replies' => $replies]);
            exit;
        }

        if (!isset($_GET['profile_owner_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing profile_owner_id']);
            exit;
        }

        $profileOwnerId = (int)$_GET['profile_owner_id'];
        $stmt = $pdo->prepare('SELECT c.id, c.user_id, c.profile_owner_id, c.parent_comment_id, c.content, c.created_at, c.updated_at, u.username AS author_username, (SELECT COUNT(*) FROM profile_comments sub WHERE sub.parent_comment_id = c.id AND sub.is_deleted = FALSE) AS reply_count FROM profile_comments c LEFT JOIN profiles u ON u.id = c.user_id WHERE c.profile_owner_id = :profile_owner AND c.parent_comment_id IS NULL AND c.is_deleted = FALSE ORDER BY c.created_at DESC LIMIT 10');
        $stmt->execute([':profile_owner' => $profileOwnerId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['comments' => $comments]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Unable to fetch comments', 'details' => $e->getMessage()]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not authenticated']);
        exit;
    }

    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JASON']);
        exit;
    }

    $action = isset($data['action']) ? trim($data['action']) : 'create';
    $profileOwnerId = isset($data['profile_owner_id']) ? (int)$data['profile_owner_id'] : null;
    $profileOwnerUsername = isset($data['profile_owner_username']) ? trim($data['profile_owner_username']) : null;
    $commentId = isset($data['comment_id']) ? (int)$data['comment_id'] : null;
    $content = trim($data['content'] ?? '');
    $parentCommentId = isset($data['parent_comment_id']) && $data['parent_comment_id'] !== null ? (int)$data['parent_comment_id'] : null;

    if (!$profileOwnerId && $profileOwnerUsername) {
        $ownerStmt = $pdo->prepare('SELECT id FROM profiles WHERE username = :username LIMIT 1');
        $ownerStmt->execute([':username' => $profileOwnerUsername]);
        $ownerRow = $ownerStmt->fetch(PDO::FETCH_ASSOC);
        if ($ownerRow) {
            $profileOwnerId = (int)$ownerRow['id'];
        }
    }

    if ($action === 'delete') {
        if (!$commentId) {
            http_response_code(400);
            echo json_encode(['error' => 'comment_id is required for delete']);
            exit;
        }
    } else {
        if (!$profileOwnerId || $content === '') {
            http_response_code(400);
            echo json_encode(['error' => 'profile_owner_id and content are required']);
            exit;
        }
    }

    // detect current user ID via profiles table.
    $currentUser = $_SESSION['username'];
    $userStmt = $pdo->prepare('SELECT id FROM profiles WHERE username = :username LIMIT 1');
    $userStmt->execute([':username' => $currentUser]);
    $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$userRow) {
        http_response_code(403);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $authorId = (int)$userRow['id'];

    try {
        if ($action === 'delete') {
            // check admin/manager role for deletion
            $roleStmt = $pdo->prepare('SELECT role FROM profiles WHERE username = :username LIMIT 1');
            $roleStmt->execute([':username' => $_SESSION['username']]);
            $roleRow = $roleStmt->fetch(PDO::FETCH_ASSOC);
            $currentRole = $roleRow ? (int)$roleRow['role'] : 0;
            if ($currentRole < 3) {
                http_response_code(403);
                echo json_encode(['error' => 'Insufficient role to delete comments']);
                exit;
            }

            $deleteStmt = $pdo->prepare('UPDATE profile_comments SET is_deleted = 1 WHERE id = :comment_id');
            $deleteStmt->execute([':comment_id' => $commentId]);

            echo json_encode(['success' => true, 'deleted_comment_id' => $commentId]);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO profile_comments (user_id, profile_owner_id, parent_comment_id, content) VALUES (:user_id, :profile_owner_id, :parent_comment_id, :content)');
        $stmt->bindValue(':user_id', $authorId, PDO::PARAM_INT);
        $stmt->bindValue(':profile_owner_id', $profileOwnerId, PDO::PARAM_INT);
        if ($parentCommentId === null) {
            $stmt->bindValue(':parent_comment_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':parent_comment_id', $parentCommentId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'comment_id' => $pdo->lastInsertId()]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Unable to save comment', 'details' => $e->getMessage()]);
    }

    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
