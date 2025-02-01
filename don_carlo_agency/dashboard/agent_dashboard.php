<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'agent') {
    header("Location: ../forms/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Fetch pending bookings
$query = "SELECT b.id, p.name AS player_name, p.current_club 
          FROM bookings b 
          JOIN players p ON b.player_id = p.id 
          WHERE b.agent_id = :agent_id AND b.status = 'pending'";

$stmt = $conn->prepare($query);
$stmt->bindParam(':agent_id', $_SESSION['user_id']);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Welcome, Agent!</h2>
    <h3>Pending Transfers</h3>
    <table>
        <tr>
            <th>Player Name</th>
            <th>Current Club</th>
            <th>Action</th>
        </tr>
        <?php foreach ($bookings as $booking) { ?>
            <tr>
                <td><?= $booking['player_name']; ?></td>
                <td><?= $booking['current_club']; ?></td>
                <td>
                    <form action="../back/accept_booking.php" method="POST">
                        <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                        <button type="submit" name="accept">Accept</button>
                        <button type="submit" name="reject">Reject</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
