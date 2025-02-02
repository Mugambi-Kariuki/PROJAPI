<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // Update booking status
    $stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();

    // Send notification
    $booking = $conn->query("SELECT player_id FROM bookings WHERE id=$booking_id")->fetch_assoc();
    $player_id = $booking['player_id'];
    $message = "Your booking has been $status.";

    $stmt = $conn->prepare("INSERT INTO notifications (player_id, message, status) VALUES (?, ?, 'unread')");
    $stmt->bind_param("is", $player_id, $message);
    $stmt->execute();
}
?>
