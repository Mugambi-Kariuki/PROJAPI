<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../forms/user login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Fetch agent performance data
$query = "SELECT * FROM agents";
$stmt = $conn->prepare($query);
$stmt->execute();
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Welcome, Admin!</h2>
    <h3>Agent Performance</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Transfers Completed</th>
            <th>Failed Transfers</th>
        </tr>
        <?php foreach ($agents as $agent) { ?>
            <tr>
                <td><?= $agent['name']; ?></td>
                <td><?= $agent['status']; ?></td>
                <td><?= $agent['successful_transfers']; ?></td>
                <td><?= $agent['failed_transfers']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
