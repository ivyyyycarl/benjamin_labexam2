<?php
// Check if user is already logged in
session_start();
if (isset($_SESSION["user_id"])) {
    // Redirect to chat if already logged in
    header("Location: chat.php");
    exit();
}
// Redirect to login page
header("Location: login.html");
exit();
?>
