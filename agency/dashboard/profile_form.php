<?php
//session_start();
require_once "../classes/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../form/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT is_verified FROM user WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(); // No arguments needed here

if ($user['is_verified'] == 0) {
    header("Location: ../form/verification.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Welcome to your dashboard!</h1>
    <a href="#" id="openProfile">Profile</a>

    <?php include "profile_form.php"; ?>

</body>
</html>
