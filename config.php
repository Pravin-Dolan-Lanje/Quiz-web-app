<?php
// Ensure no output before this
ob_start(); // Start output buffering

// Then start your session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Helper functions
function redirect($url) {
    header("Location: " . $url);
    ob_end_flush(); // Send output buffer and turn off buffering
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] === 'admin');
}

function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}
?>