<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$date = date('Y-m-d');

$stmt = $conn->prepare("SELECT type, TIME FROM attendance WHERE user_id = ? AND date = ? ORDER BY time DESC LIMIT 2");
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($type, $time);

$attendance = ['checkInTime' => null, 'checkOutTime' => null];
while ($stmt->fetch()) {
    if ($type === 'check_in') {
        $attendance['checkInTime'] = $time;
    } elseif ($type === 'check_out') {
        $attendance['checkOutTime'] = $time;
    }
}

echo json_encode($attendance);

$stmt->close();
$conn->close();
?>
