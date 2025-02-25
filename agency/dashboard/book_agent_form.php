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

if (isset($_GET['agent_id'])) {
    $agent_id = $_GET['agent_id'];
    $user_id = $_SESSION['user_id'];

    // Update agent status to 'Occupied'
    $stmt = $conn->prepare("UPDATE agents SET status = 'Occupied' WHERE agent_id = ?");
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();

    // Insert booking record (assuming you have a bookings table)
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, agent_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $agent_id);
    $stmt->execute();

    header("Location: home.php");
    exit();
} else {
    echo "Invalid agent ID.";
}
?>
