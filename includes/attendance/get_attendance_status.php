<?php
session_start();
include_once '../db.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');

$stmt = $conn->prepare("SELECT checkin_time, checkout_time FROM attendance WHERE user_id = ? AND date = ?");
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($checkin_time, $checkout_time);

$attendance = ['checkInTime' => null, 'checkOutTime' => null];
if ($stmt->fetch()) {
    $attendance['checkInTime'] = $checkin_time;
    $attendance['checkOutTime'] = $checkout_time;
}

echo json_encode($attendance);

$stmt->close();
$conn->close();
?>
