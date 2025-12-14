<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // --- MODIFIED: Querying database with PLAIN TEXT password ---
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a user record was found, credentials match (plain text)
    if ($user) {
        // Login Success
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        
        header("Location: chat.php");
        exit();
    } else {
        echo "Invalid username or password. <a href='login.html'>Try again</a>";
    }
}
?>