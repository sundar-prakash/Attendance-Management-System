<?php
include 'db.php'; // Include database connection

// Function to get user details
function get_user_details($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name);
    $stmt->fetch();
    
    return ['name' => $name];
}

// Function to get user attendance records
function get_attendance($user_id) {
    global $conn;
    $date = date('Y-m-d');
    
    $stmt = $conn->prepare("SELECT type, time FROM attendance WHERE user_id = ? AND date = ?");
    $stmt->bind_param("is", $user_id, $date);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($type, $time);
    
    $attendance = [];
    while ($stmt->fetch()) {
        $attendance[] = ['type' => $type, 'time' => $time];
    }
    
    return $attendance;
}
?>
