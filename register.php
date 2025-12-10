<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        die("Please fill out all fields.");
    }

    // Hash the password for security
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed]);
        
        // Redirect to login page on success
        header("Location: login.html");
        exit();
    } catch (PDOException $e) {
        // Handle duplicate username error
        if ($e->getCode() == 23000) {
            echo "Username already taken. <a href='register.html'>Try again</a>";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>