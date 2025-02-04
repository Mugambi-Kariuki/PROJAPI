<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'player') {
    header("Location: ../forms/user login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Fetch available agents
$query = "SELECT * FROM agents WHERE status = 'free'";
$stmt = $conn->prepare($query);
$stmt->execute();
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Welcome, Player!</h2>
    <h3>Available Agents</h3>
    <table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Success Rate</th>
            <th scope="col">Charge (%)</th>
            <th scope="col">Book</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agents as $agent) { ?>
            <tr>
                <td><?= htmlspecialchars($agent['name']); ?></td>
                <td><?= htmlspecialchars($agent['success_rate']); ?>%</td>
                <td><?= htmlspecialchars($agent['charge']); ?>%</td>
                <td>
                    <form action="../back/book_agent.php" method="POST">
                        <input type="hidden" name="agent_id" value="<?= htmlspecialchars($agent['id']); ?>">
                        <button type="submit" class="btn btn-primary">Book</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
