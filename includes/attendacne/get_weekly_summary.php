<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$startDate = date('Y-m-d', strtotime('monday this week'));
$endDate = date('Y-m-d', strtotime('sunday this week'));

// Prepare statement to get check-in and check-out times
$stmt = $conn->prepare("
    SELECT date, TIMEDIFF(MAX(checkout_time), MIN(checkin_time)) AS duration
    FROM attendance
    WHERE user_id = ? AND date BETWEEN ? AND ? AND checkin_time IS NOT NULL AND checkout_time IS NOT NULL
    GROUP BY date
");
$stmt->bind_param("iss", $user_id, $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['date']] = $row['duration'];
}

// Prepare arrays for days of the week and their corresponding durations
$dates = [];
$durations = [];
for ($i = 0; $i <= 6; $i++) {
    $currentDate = date('Y-m-d', strtotime($startDate . ' + ' . $i . ' days'));
    $dayOfWeek = date('D', strtotime($currentDate)); // Day of the week abbreviation
    $dates[] = $dayOfWeek;

    // Convert duration to minutes
    $duration = isset($data[$currentDate]) ? $data[$currentDate] : '00:00:00';
    $parts = explode(':', $duration);
    $minutes = ($parts[0] * 60) + $parts[1] + ($parts[2] / 60);
    $durations[] = $minutes;
}

echo json_encode(['dates' => $dates, 'durations' => $durations]);

$stmt->close();
$conn->close();
?>
