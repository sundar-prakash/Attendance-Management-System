<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$type = isset($_POST['type']) ? $_POST['type'] : '';

if ($type === 'check_in' || $type === 'check_out') {
    $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, time, type) VALUES (?, CURDATE(), CURTIME(), ?)");
    $stmt->bind_param("is", $user_id, $type);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid type']);
}

$conn->close();
?>
