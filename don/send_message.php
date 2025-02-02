<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$sender_id = $_SESSION['user']['id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();

echo json_encode(["status" => "success"]);
?>
