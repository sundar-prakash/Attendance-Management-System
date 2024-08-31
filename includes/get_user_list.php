<?php
session_start();
include 'db.php'; // Ensure the database connection is included

$response = ['users' => []];

if (isset($_SESSION['user_id']) && $_SESSION['is_admin']) {
    $stmt = $conn->prepare("SELECT id, username, name, email, role FROM users");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response['users'][] = $row;
    }

    $stmt->close();
} else {
    $response['error'] = 'Unauthorized';
}

echo json_encode($response);
?>
