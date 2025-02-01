<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: ../forms/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = isset($_POST['accept']) ? 'accepted' : 'rejected';

    $query = "UPDATE bookings SET status = :status WHERE id = :booking_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':booking_id', $booking_id);

    if ($stmt->execute()) {
        echo "Booking $status!";
    } else {
        echo "Error!";
    }
}
?>
