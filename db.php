<?php
$host = "localhost";
$dbname = "chat_app";
$username = "root"; // Change if your DB user is different
$password = "";     // Change if your DB password is different

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>