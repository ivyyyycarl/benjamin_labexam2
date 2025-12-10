<?php
session_start();
require "db.php";

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Handle sending a message
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $msg = trim($_POST["message"]);
    if (!empty($msg)) {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION["user_id"], $msg]);
    }
    // Prevent form resubmission on refresh
    header("Location: chat.php");
    exit();
}

// Fetch messages
$messages = $pdo->query("
    SELECT messages.message, users.username, messages.created_at
    FROM messages
    JOIN users ON users.id = messages.user_id
    ORDER BY messages.id ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room - Chat App</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container">
    <header>
        <div>
            <h1>💬 Chat Room</h1>
        </div>
        <div id="user-info">
            <span>👤 <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></span>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <section class="messages" id="messages">
        <?php foreach ($messages as $m): ?>
            <div class="msg">
                <span class="user"><?= htmlspecialchars($m["username"]) ?></span>
                <span class="text"><?= htmlspecialchars($m["message"]) ?></span>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="send-message">
        <form method="POST" action="chat.php">
            <input name="message" type="text" placeholder="Type your message here..." required autocomplete="off">
            <button type="submit">Send</button>
        </form>
    </section>
</main>

<script>
    // Auto-scroll to bottom
    document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
    // Refresh chat every 2 seconds
    setInterval(() => { location.reload(); }, 2000);
</script>
</body>
</html>