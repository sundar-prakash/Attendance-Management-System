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
    $date = date('Y-m-d');
    
    // Check if there's an existing record for today
    $stmt = $conn->prepare("SELECT checkin_time, checkout_time FROM attendance WHERE user_id = ? AND date = ?");
    $stmt->bind_param("is", $user_id, $date);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($checkin_time, $checkout_time);
    $stmt->fetch();
    
    if ($type === 'check_in') {
        if (!empty($checkin_time)) {
            echo json_encode(['success' => false, 'message' => 'Already checked in today']);
            exit();
        }
        // Insert check-in time
        $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, checkin_time) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE checkin_time = VALUES(checkin_time)");
        $stmt->bind_param("iss", $user_id, $date, date('H:i:s'));
    } else if ($type === 'check_out') {
        if (empty($checkin_time)) {
            echo json_encode(['success' => false, 'message' => 'You need to check in first']);
            exit();
        }
        if (!empty($checkout_time)) {
            echo json_encode(['success' => false, 'message' => 'Already checked out today']);
            exit();
        }
        // Update check-out time
        $stmt = $conn->prepare("UPDATE attendance SET checkout_time = ? WHERE user_id = ? AND date = ?");
        $stmt->bind_param("sis", date('H:i:s'), $user_id, $date);
    }
    
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
