<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'player') {
    $player_id = $_SESSION['user']['id'];
    $agent_id = $_POST['agent_id'];

    $stmt = $conn->prepare("INSERT INTO bookings (player_id, agent_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $player_id, $agent_id);
    if ($stmt->execute()) {
        header("Location: player_dashboard.php?success=Booked successfully!");
    } else {
        header("Location: player_dashboard.php?error=Failed to book.");
    }
}
?>
