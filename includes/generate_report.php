<?php
session_start();
include 'db.php';
require 'PHP_XLSXWriter/xlsxwriter.class.php'; // Adjust the path

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['is_admin']) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Prepare the SQL statement
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

    // Prepare the data for the XLSX file
    $header = ['Name', 'Username', 'Date', 'Time', 'Type'];
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = [$row['name'], $row['username'], $row['date'], $row['time'], $row['type']];
    }

    // Create the XLSXWriter instance
    $writer = new XLSXWriter();
    $writer->writeSheetHeader('Sheet1', $header);
    $writer->writeSheet($data);

    $file = 'simple.xlsx';
    $writer->writeToFile($file);
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);
        exit;
    }
    $stmt->close();
} else {
    $response['error'] = 'Unauthorized access or invalid request method.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>