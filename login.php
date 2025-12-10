<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
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