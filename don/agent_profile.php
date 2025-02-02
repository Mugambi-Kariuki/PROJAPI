<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}
require 'db.php';

$agent_id = $_SESSION['user']['id'];
$agent = $conn->query("SELECT * FROM agents WHERE user_id = $agent_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = $_POST['bio'];
    $stmt = $conn->prepare("UPDATE agents SET bio=? WHERE user_id=?");
    $stmt->bind_param("si", $bio, $agent_id);
    $stmt->execute();
    header("Location: agent_profile.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Agent Profile</h2>
    <form method="POST">
        <label>Bio:</label>
        <textarea name="bio" class="form-control"><?php echo $agent['bio']; ?></textarea>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>
</body>
</html>
