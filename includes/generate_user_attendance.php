<?php
session_start();
include "db.php";
require "PhpXlsxGenerator.php"; // Adjust the path

error_reporting(E_ALL);
ini_set("display_errors", 1);

function filterData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$response = ["success" => false];

if (isset($_SESSION['user_id']) && $_SESSION['is_admin']) {
    if (!isset($_GET['id'])) {
        echo 'No user ID provided.';
        exit();
    }

    $user_id = sanitizeInput($_GET['id']);
    
    // Prepare the SQL statement with TIMEDIFF to calculate duration
    $stmt = $conn->prepare("
        SELECT u.name, u.username, a.date, a.checkin_time, a.checkout_time, 
               TIMEDIFF(a.checkout_time, a.checkin_time) AS duration
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        WHERE a.user_id = ?
        ORDER BY a.date
    ");

    // Bind parameters
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch user details for filename
    $user_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_name = $user_result->fetch_assoc()['name'];
    
    // Generate filename
    $fileName = sanitizeInput($user_name) . "_attendance_" . date("Y-m-d") . ".xls";

    // Prepare the data for the XLSX file
    $header = ["Name", "Username", "Date", "Check-in Time", "Check-out Time", "Duration"];
    // Display column names as first row
    $excelData = implode("\t", array_values($header)) . "\n";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data = [
                $row["name"],
                $row["username"],
                $row["date"],
                $row["checkin_time"],
                $row["checkout_time"],
                $row["duration"], // Add duration to data
            ];
            array_walk($data, "filterData");
            $excelData .= implode("\t", array_values($data)) . "\n";
        }
    } else {
        $excelData .= "No records found..." . "\n";
    }

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    echo $excelData;
    exit();

    $stmt->close();
    $user_stmt->close();
} else {
    echo 'Unauthorized access or invalid request method.';
}
?>
