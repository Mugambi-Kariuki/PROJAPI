<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

ob_start(); // Ensure no output before headers

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
    $sheet->setTitle('Players Report');
    $spreadsheet->setActiveSheetIndex(0); // Ensure active sheet is set

    // Fetch data
    $result = $conn->query("SELECT * FROM footballers");
    if (!$result) {
        throw new Exception("Failed to fetch players: " . $conn->error);
    }

    // Fetch column names
    $fields = $result->fetch_fields();
    $col = 'A';
    foreach ($fields as $field) {
        $sheet->setCellValue($col . '1', $field->name);
        $col++;
    }

    // Populate Data
    $row = 2;
    while ($player = $result->fetch_assoc()) {
        $col = 'A';
        foreach ($fields as $field) {
            $sheet->setCellValue($col . $row, $player[$field->name]);
            $col++;
        }
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'players_report_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Ensure no extra output
    ob_end_clean();

    // Proper headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    header('Expires: 0');
    header('Pragma: public');

    $writer->save('php://output');
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    if ($conn) {
        $conn->close();
    }
}
?>
