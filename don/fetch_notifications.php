<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'player') {
    echo json_encode([]);
    exit;
}

$player_id = $_SESSION['user']['id'];
$notifications = $conn->query("SELECT * FROM notifications WHERE player_id = $player_id AND status = 'unread' ORDER BY created_at DESC");

$data = [];
while ($notif = $notifications->fetch_assoc()) {
    $data[] = $notif;
}

echo json_encode($data);

$conn->query("UPDATE notifications SET status = 'read' WHERE player_id = $player_id");

?>
