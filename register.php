<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username === "" || $password === "") {
        die("Please fill out all fields.");
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    try {
        $stmt->execute([$username, $hashed]);
        header("Location: login.html");
        exit();
    } catch (PDOException $e) {
        echo "Username already taken.";
    }
}
?>
