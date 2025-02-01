<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'player') {
    header("Location: ../forms/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agent_id = $_POST['agent_id'];
    $player_id = $_SESSION['user_id'];

    $query = "INSERT INTO bookings (player_id, agent_id, status) VALUES (:player_id, :agent_id, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':player_id', $player_id);
    $stmt->bindParam(':agent_id', $agent_id);

    if ($stmt->execute()) {
        echo "Booking request sent!";
    } else {
        echo "Error!";
    }
}
?>
