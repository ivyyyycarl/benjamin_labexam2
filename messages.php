<?php
require "db.php";
header('Content-Type: application/json; charset=utf-8');

// return last 200 messages
try {
    $stmt = $pdo->prepare(
        "SELECT messages.id, messages.message, users.username, messages.created_at
         FROM messages
         JOIN users ON users.id = messages.user_id
         ORDER BY messages.id ASC
         LIMIT 200"
    );
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // basic structure for JSON
    echo json_encode($rows);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

?>
