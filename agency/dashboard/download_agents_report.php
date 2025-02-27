<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require '../vendor/autoload.php'; 
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
    $sheet->setTitle('Agents Report');
    $result = $conn->query("SELECT * FROM agents");
    if (!$result) {
        throw new Exception("Failed to fetch agents: " . $conn->error);
    }

    //column headers
    $sheet->setCellValue('A1', 'Agent ID');
    $sheet->setCellValue('B1', 'Name');

    // Populate data
    $row = 2;
    while ($agent = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $agent['agent_id']);
        $sheet->setCellValue('B' . $row, $agent['name']);
        $row++;
    }

    $fileName = 'agents_report_' . date('Y-m-d_H-i-s') . '.xlsx';

    //headers for downloading
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output'); //output first instead of saving
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    if ($conn) {
        $conn->close();
    }
}
