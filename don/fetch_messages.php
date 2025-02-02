<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user']['id'];
$receiver_id = $_GET['receiver_id'];

$messages = $conn->query("SELECT * FROM messages WHERE (sender_id = $user_id AND receiver_id = $receiver_id) OR (sender_id = $receiver_id AND receiver_id = $user_id) ORDER BY sent_at");

$data = [];
while ($msg = $messages->fetch_assoc()) {
    $data[] = $msg;
}

echo json_encode($data);
?>
