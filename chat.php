<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $msg = trim($_POST["message"]);
    if ($msg !== "") {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION["user_id"], $msg]);
    }
    header("Location: chat.php");
    exit();
}

$messages = $pdo->query("
    SELECT messages.message, users.username 
    FROM messages
    JOIN users ON users.id = messages.user_id
    ORDER BY messages.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat Room</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container">
    <header>
        <h1>Chat Room</h1>
        <p>Logged in as <strong><?= $_SESSION["username"] ?></strong> |
        <a href="logout.php">Logout</a></p>
    </header>

    <form method="POST">
        <input name="message" type="text" placeholder="Type message..." required>
        <button type="submit">Send</button>
    </form>

    <div class="messages">
        <?php foreach ($messages as $m): ?>
            <div class="msg">
                <strong><?= htmlspecialchars($m["username"]) ?>:</strong>
                <?= htmlspecialchars($m["message"]) ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
