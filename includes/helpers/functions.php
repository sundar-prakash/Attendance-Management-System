<?php
include_once 'includes/db.php'; // Include database connection

// Function to get user details
function get_user_name($user_id) {
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
    
    $stmt = $conn->prepare("SELECT checkin_time, checkout_time FROM attendance WHERE user_id = ? AND date = ?");
    $stmt->bind_param("is", $user_id, $date);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($checkin_time, $checkout_time);
    
    $attendance = [];
    if ($stmt->fetch()) {
        $attendance = ['checkin_time' => $checkin_time, 'checkout_time' => $checkout_time];
    }
    
    return $attendance;
}

function get_user_details($user_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT username, name, email, role
        FROM users
        WHERE id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return []; // Return an empty array if no user is found
    }
}

function get_user_attendance($user_id, $limit, $offset) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT date, checkin_time, checkout_time, TIMEDIFF(checkout_time, checkin_time) AS duration
        FROM attendance
        WHERE user_id = ?
        ORDER BY date DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("iii", $user_id, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $attendance = [];
    while ($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }
    return $attendance;
}

function get_total_attendance_entries($user_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM attendance
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total'];
}



?>
