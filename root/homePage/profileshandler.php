<?php
header('Content-Type: application/json');

include "../scripts/databaseConnection.php";

$pdo = create_conn();

$chunk = isset($_GET['chunk']) ? (int)$_GET['chunk'] : 1;
$limit = 10;
$offset = ($chunk - 1) * $limit;

session_start();

$username = $_SESSION["username"] ?? null;
$user_preference = $_SESSION['preference'] ?? null;
$currentUserRole = 0;
$profiles = [];
$hasMore = false;
$totalCount = 0;

if ($username) {
    $roleStmt = $pdo->prepare('SELECT role FROM profiles WHERE username = :username LIMIT 1');
    $roleStmt->execute([':username' => $username]);
    $row = $roleStmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $currentUserRole = (int)$row['role'];
    }
}

try {
    $sql = "SELECT 
            p.id, 
            p.username, 
            p.realname, 
            p.zipcode, 
            p.bio, 
            p.salary, 
            p.preference, 
            p.email, 
            COALESCE(p.likes, 0) AS likes, 
            p.role, 
            COALESCE(p.is_softbanned, 0) AS is_softbanned 
        FROM profiles p 
        WHERE 1=1 ";

    $params = [];

    // dont include yourself lol
    if ($username) {
        $sql .= " AND p.username != :cur_username ";
        $params[':cur_username'] = $username;
    }

    // normal users should not see softbanned profiles
    if ($currentUserRole < 3) {
        $sql .= " AND p.is_softbanned = 0 ";
    }

    $sql .= " ORDER BY p.is_softbanned ASC, p.likes DESC, p.id ASC 
              LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $profiles = $stmt->fetchAll();

    $countSql = "SELECT COUNT(*) as total 
                 FROM profiles p 
                 WHERE 1=1 ";

    $countParams = [];

    if ($username) {
        $countSql .= " AND p.username != :cur_username ";
        $countParams[':cur_username'] = $username;
    }

    if ($currentUserRole < 3) {
        $countSql .= " AND p.is_softbanned = 0 ";
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($countParams as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalCount = $countStmt->fetchColumn();

    // Calculate if there are more results
    $hasMore = ($offset + $limit) < $totalCount;

    // Format the response
    foreach ($profiles as &$profile) {

        $profile['salary_formatted'] = '$' . number_format($profile['salary']);

        unset($profile['passhash']); // do not remove ever!!
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

echo json_encode([
    'profiles' => $profiles,
    'hasMore' => $hasMore,
    'chunk' => $chunk,
    'totalCount' => (int)$totalCount,
    'currentUserRole' => $currentUserRole
]);
