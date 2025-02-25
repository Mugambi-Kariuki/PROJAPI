<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../form/login.php");
    exit();
}

require_once "../classes/database.php";
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = $_POST['agent_id'];
    $target_country = $_POST['target_club'];
    $years = $_POST['years'];
    $expected_salary = $_POST['expected_salary'];

    $stmt = $conn->prepare("INSERT INTO bookings (agent_id, user_id, target_club, years, expected_salary) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("iisii", $agent_id, $_SESSION['user_id'], $target_country, $years, $expected_salary);

    if ($stmt->execute()) {
        header("Location: booking_success.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
