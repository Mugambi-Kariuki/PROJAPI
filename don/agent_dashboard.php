<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'agent') {
    header("Location: login.php");
    exit;
}
require 'db.php';

$agent_id = $_SESSION['user']['id'];
$bookings = $conn->query("SELECT b.id, u.name AS player_name, b.status FROM bookings b JOIN players p ON b.player_id = p.user_id JOIN users u ON p.user_id = u.id WHERE b.agent_id = $agent_id AND b.status = 'pending'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Agent Dashboard</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Player Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($booking = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $booking['player_name']; ?></td>
                    <td><?php echo ucfirst($booking['status']); ?></td>
                    <td>
                        <button class="btn btn-success accept" data-id="<?php echo $booking['id']; ?>">Accept</button>
                        <button class="btn btn-danger reject" data-id="<?php echo $booking['id']; ?>">Reject</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".accept, .reject").click(function() {
        let id = $(this).data("id");
        let status = $(this).hasClass("accept") ? "accepted" : "rejected";
        $.post("update_booking.php", { booking_id: id, status: status }, function(response) {
            location.reload();
        });
    });
});
</script>

</body>
</html>
