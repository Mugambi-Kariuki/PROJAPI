<?php
require_once '../classes/database.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = $_POST['agent_id'];
    $target_club = $_POST['target_club'];
    $years = $_POST['years'];
    $expected_salary = $_POST['expected_salary'];

    $database = new Database();
    $conn = $database->getConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "INSERT INTO bookings (agent_id, target_club, years, expected_salary, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isiii", $agent_id, $target_club, $years, $expected_salary, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
