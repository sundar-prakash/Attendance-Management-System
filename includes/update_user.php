<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $username = $data['username'];
    $name = $data['name'];
    $email = $data['email'];
    $role = $data['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $name, $email, $role, $id);
    $success = $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => $success]);
}
?>
