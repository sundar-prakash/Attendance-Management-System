<?php
session_start();
include_once '../db.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['is_admin']) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, name, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $hashedPassword, $name, $email, $role);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['error'] = "Failed to add user: " . $stmt->error;
    }

    $stmt->close();
}

echo json_encode($response);
?>
