<?php
// Database connection settings
$host = 'localhost';
$db = 'attendance_system';
$user = 'root';
$pass = ''; // Change to your database password

// Create a new MySQLi object for database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
