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
        <form id="sendForm">
            <input id="messageInput" name="message" type="text" placeholder="Type your message here..." required autocomplete="off">
            <button id="sendBtn" type="submit">Send</button>
        </form>
    </section>
</main>

<script>
    const messagesEl = document.getElementById('messages');
    const form = document.getElementById('sendForm');
    const input = document.getElementById('messageInput');

    // Helper to render messages (replace contents)
    function renderMessages(messages) {
        messagesEl.innerHTML = '';
        messages.forEach(m => {
            const div = document.createElement('div');
            div.className = 'msg';
            const user = document.createElement('span');
            user.className = 'user';
            user.textContent = m.username;
            const text = document.createElement('span');
            text.className = 'text';
            text.textContent = m.message;
            div.appendChild(user);
            div.appendChild(text);
            messagesEl.appendChild(div);
        });
        // scroll to bottom
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    // Fetch latest messages from server
    async function fetchMessages() {
        try {
            const res = await fetch('messages.php');
            if (!res.ok) throw new Error('Network response was not ok');
            const data = await res.json();
            renderMessages(data);
        } catch (err) {
            console.error('Failed fetching messages', err);
        }
    }

    // Send message via AJAX
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;
        try {
            const res = await fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ message: msg })
            });
            if (!res.ok) throw new Error('Send failed');
            input.value = '';
            await fetchMessages();
        } catch (err) {
            console.error('Failed to send message', err);
        }
    });

    // Poll for new messages every 2s
    fetchMessages();
    setInterval(fetchMessages, 2000);
</script>
</body>
</html>