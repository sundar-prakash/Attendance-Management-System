<?php
session_start();
include_once "../db.php";
require "../helpers/PhpXlsxGenerator.php"; // Adjust the path
error_reporting(E_ALL);
ini_set("display_errors", 1);

function filterData(&$str) {
    if (is_null($str)) {
        $str = '';  // Or handle it in another appropriate way, like setting a default value
        return;
    }

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
$fileName = "attendance-data_" . date("Y-m-d") . ".xls";

if (isset($_SESSION['user_id']) && $_SESSION['is_admin']) {
    $startDate = sanitizeInput($_GET['startDate']);
    $endDate = sanitizeInput($_GET['endDate']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("
        SELECT u.name, u.username, a.date, a.checkin_time, a.checkout_time
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        WHERE a.date BETWEEN ? AND ?
        ORDER BY u.id, a.date
    ");

    // Bind parameters
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the data for the XLSX file
    $header = ["Name", "Username", "Date", "Check-in Time", "Check-out Time"];
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
} else {
    $response['error'] = 'Unauthorized access or invalid request method.';
}
?>
