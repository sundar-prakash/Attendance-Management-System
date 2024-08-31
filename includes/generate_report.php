<?php
session_start();
include 'db.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['is_admin']) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $stmt = $conn->prepare("
        SELECT u.name, u.username, a.date, a.time, a.type
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        WHERE a.date BETWEEN ? AND ?
        ORDER BY u.id, a.date, a.time
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $report = [];
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }

    $response['success'] = true;
    $response['report'] = $report;

    $stmt->close();
}

echo json_encode($response);
?>
