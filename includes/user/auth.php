<?php
include_once '../db.php'; // Include the database connection

// Function to verify user credentials
function verify_credentials($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password))  {
        return true;
    }
    return false;
}

// Function to check if the user is an admin
function is_admin($username) {
    global $conn;
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role);
    $stmt->fetch();
    return ($role === 'admin');
}

// Function to get the user ID
function get_user_id($username) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id);
    $stmt->fetch();
    return $id;
}

// Function to check if the user is within the allowed location (dummy implementation)

?>
