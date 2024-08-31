<?php
session_start();
include "db.php";

header('Content-Type: application/json');

$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Prepare the SQL statement
$stmt = $conn->prepare("
    SELECT id, username, name, email, role
    FROM users
    WHERE username LIKE ? OR name LIKE ?
    ORDER BY id
");

// Bind parameters
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(['users' => $users]);

$stmt->close();
?>
