<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        die("Please fill out all fields.");
    }

    // --- MODIFIED: Storing password in PLAIN TEXT as required by the exam ---
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        // EXECUTE with the raw $password variable
        $stmt->execute([$username, $password]); 
        
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