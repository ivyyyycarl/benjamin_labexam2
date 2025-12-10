<?php
$host = "localhost";
$username = "root";
$password = "";

try {
    // Connect without specifying database
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS chat_app");
    echo "Database created or already exists.<br>";
    
    // Select the database
    $pdo->exec("USE chat_app");
    
    // Create users table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Users table created or already exists.<br>";
    
    // Create messages table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "Messages table created or already exists.<br>";
    
    echo "<h2 style='color: green;'>✓ Database setup complete!</h2>";
    echo "<p><a href='login.html'>Go to Login</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Setup failed: " . $e->getMessage() . "</h2>";
}
?>
