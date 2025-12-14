<?php
session_start();
require "db.php";

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit();
}

$msg = trim($_POST['message'] ?? '');
if ($msg === '') {
    http_response_code(400);
    echo json_encode(["error" => "Empty message"]);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $msg]);
    echo json_encode(["success" => true]);
    exit();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}

?>
