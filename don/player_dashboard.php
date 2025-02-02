<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'player') {
    header("Location: login.php");
    exit;
}
require 'db.php';

$player_id = $_SESSION['user']['id'];
$notifications = $conn->query("SELECT * FROM notifications WHERE player_id = $player_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Player Notifications</h2>
    <ul class="list-group">
        <?php while ($notif = $notifications->fetch_assoc()): ?>
            <li class="list-group-item <?php echo $notif['status'] == 'unread' ? 'bg-light' : ''; ?>">
                <?php echo $notif['message']; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
<div class="container mt-4">
    <h2>Player Notifications <span id="notifCount" class="badge bg-danger">0</span></h2>
    <ul class="list-group" id="notificationsList"></ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function fetchNotifications() {
    $.ajax({
        url: "fetch_notifications.php",
        method: "GET",
        success: function(data) {
            let notifications = JSON.parse(data);
            $("#notifCount").text(notifications.length);
            $("#notificationsList").empty();
            notifications.forEach(notif => {
                $("#notificationsList").append(`<li class="list-group-item">${notif.message}</li>`);
            });
        }
    });
}

setInterval(fetchNotifications, 5000);  // Fetch every 5 seconds
fetchNotifications();
</script>

</body>
</html>
