<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require '../vendor/autoload.php'; // Updated path
include '../classes/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $result = $conn->query("SELECT * FROM bookings");
    if (!$result) {
        throw new Exception("Failed to fetch bookings: " . $conn->error);
    }
    $sheet->setCellValue('A1', 'Bookings');
    $sheet->setCellValue('A2', 'Booking ID');
    $sheet->setCellValue('B2', 'Agent ID');
    $sheet->setCellValue('C2', 'Date');
    $sheet->setCellValue('D2', 'Time');
    $row = 3;
    while ($booking = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $booking['booking_id']);
        $sheet->setCellValue('B' . $row, $booking['agent_id']);
        $sheet->setCellValue('C' . $row, $booking['date']);
        $sheet->setCellValue('D' . $row, $booking['time']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'bookings_report_' . date('Y-m-d_H-i-s') . '.xlsx';
    $filePath = __DIR__ . '/' . $fileName;

    if (!is_writable(__DIR__)) {
        throw new Exception("Directory is not writable: " . __DIR__);
    }

    $writer->save($filePath);

    if (!file_exists($filePath)) {
        throw new Exception("File not created: " . $filePath);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);

    unlink($filePath);
} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    if ($conn) {
        $conn->close();
    }
}
?>
