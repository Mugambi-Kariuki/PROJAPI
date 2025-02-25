<?php
require '../../vendor/autoload.php'; // Adjust the path to your vendor directory
include '../classes/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

try {
    error_log("Starting report generation...");

    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        throw new Exception("PhpSpreadsheet library is not loaded correctly.");
    }

    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Fetch and write users data
    $result = $conn->query("SELECT * FROM user");
    if (!$result) {
        throw new Exception("Failed to fetch users: " . $conn->error);
    }
    $sheet->setCellValue('A1', 'Users');
    $sheet->setCellValue('A2', 'User ID');
    $sheet->setCellValue('B2', 'Username');
    $sheet->setCellValue('C2', 'Email');
    $row = 3;
    while ($user = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $user['user_id']);
        $sheet->setCellValue('B' . $row, $user['username']);
        $sheet->setCellValue('C' . $row, $user['email']);
        $row++;
    }

    // Fetch and write agents data
    $result = $conn->query("SELECT * FROM agents");
    if (!$result) {
        throw new Exception("Failed to fetch agents: " . $conn->error);
    }
    $sheet->setCellValue('E1', 'Agents');
    $sheet->setCellValue('E2', 'Agent ID');
    $sheet->setCellValue('F2', 'Name');
    $row = 3;
    while ($agent = $result->fetch_assoc()) {
        $sheet->setCellValue('E' . $row, $agent['agent_id']);
        $sheet->setCellValue('F' . $row, $agent['name']);
        $row++;
    }

    // Fetch and write players data
    $result = $conn->query("SELECT * FROM footballers");
    if (!$result) {
        throw new Exception("Failed to fetch players: " . $conn->error);
    }
    $sheet->setCellValue('H1', 'Players');
    $sheet->setCellValue('H2', 'Player ID');
    $sheet->setCellValue('I2', 'Name');
    $row = 3;
    while ($player = $result->fetch_assoc()) {
        $sheet->setCellValue('H' . $row, $player['footballer_id']);
        $sheet->setCellValue('I' . $row, $player['name']);
        $row++;
    }

    // Fetch and write bookings data
    $result = $conn->query("SELECT * FROM bookings");
    if (!$result) {
        throw new Exception("Failed to fetch bookings: " . $conn->error);
    }
    $sheet->setCellValue('K1', 'Bookings');
    $sheet->setCellValue('K2', 'Booking ID');
    $sheet->setCellValue('L2', 'Agent ID');
    $sheet->setCellValue('M2', 'Date');
    $sheet->setCellValue('N2', 'Time');
    $row = 3;
    while ($booking = $result->fetch_assoc()) {
        $sheet->setCellValue('K' . $row, $booking['booking_id']);
        $sheet->setCellValue('L' . $row, $booking['agent_id']);
        $sheet->setCellValue('M' . $row, $booking['date']);
        $sheet->setCellValue('N' . $row, $booking['time']);
        $row++;
    }

    // Fetch and write clubs data
    $result = $conn->query("SELECT * FROM clubs");
    if (!$result) {
        throw new Exception("Failed to fetch clubs: " . $conn->error);
    }
    $sheet->setCellValue('P1', 'Clubs');
    $sheet->setCellValue('P2', 'Club ID');
    $sheet->setCellValue('Q2', 'Name');
    $sheet->setCellValue('R2', 'Location');
    $row = 3;
    while ($club = $result->fetch_assoc()) {
        $sheet->setCellValue('P' . $row, $club['club_id']);
        $sheet->setCellValue('Q' . $row, $club['club_name']);
        $sheet->setCellValue('R' . $row, $club['location']);
        $row++;
    }

    // Fetch and write transfers data
    $result = $conn->query("SELECT * FROM transfers");
    if (!$result) {
        throw new Exception("Failed to fetch transfers: " . $conn->error);
    }
    $sheet->setCellValue('T1', 'Transfers');
    $sheet->setCellValue('T2', 'Transfer ID');
    $sheet->setCellValue('U2', 'Player ID');
    $sheet->setCellValue('V2', 'From Club');
    $sheet->setCellValue('W2', 'To Club');
    $row = 3;
    while ($transfer = $result->fetch_assoc()) {
        $sheet->setCellValue('T' . $row, $transfer['transfer_id']);
        $sheet->setCellValue('U' . $row, $transfer['footballer_id']);
        $sheet->setCellValue('V' . $row, $transfer['from_club']);
        $sheet->setCellValue('W' . $row, $transfer['to_club']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'report_' . date('Y-m-d_H-i-s') . '.xlsx';
    $filePath = __DIR__ . '/' . $fileName; // Adjust the path to save the file

    if (!is_writable(__DIR__)) {
        throw new Exception("Directory is not writable: " . __DIR__);
    }

    $writer->save($filePath);

    if (!file_exists($filePath)) {
        throw new Exception("File not created: " . $filePath);
    }

    error_log("File created successfully: " . $filePath);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);

    // Optionally, delete the file after download
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
