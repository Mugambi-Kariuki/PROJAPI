<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'player') {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Database connection

$player_id = $_SESSION['user']['id'];

// Fetch available agents
$agents = $conn->query("SELECT a.id, u.name, a.success_rate, a.fees, a.availability FROM agents a JOIN users u ON a.user_id = u.id WHERE a.availability = 'free'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Agent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Book an Agent</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Agent Name</th>
                <th>Success Rate</th>
                <th>Fees</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($agent = $agents->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $agent['name']; ?></td>
                    <td><?php echo $agent['success_rate']; ?>%</td>
                    <td>$<?php echo $agent['fees']; ?></td>
                    <td>
                        <form action="book_agent_action.php" method="post">
                            <input type="hidden" name="agent_id" value="<?php echo $agent['id']; ?>">
                            <button type="submit" class="btn btn-primary">Book</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
