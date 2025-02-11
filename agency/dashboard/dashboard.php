<?php
session_start();
require_once "classes/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT is_verified FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['is_verified'] == 0) {
    header("Location: verify.php");
    exit();
}

echo "Welcome to your dashboard!";
?>
