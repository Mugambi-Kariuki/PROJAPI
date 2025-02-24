<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../form/login.php");
    exit();
}

require_once "../classes/database.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

$agent_id = $_SESSION['user_id'];

// Fetch total transfers
$stmt = $conn->prepare("SELECT COUNT(*) AS total_transfers FROM transfers WHERE agent_id = ?");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$total_transfers = $result->fetch_assoc()['total_transfers'];

// Fetch successful transfers
$stmt = $conn->prepare("SELECT COUNT(*) AS successful_transfers FROM transfers WHERE agent_id = ? AND status = 'successful'");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$successful_transfers = $result->fetch_assoc()['successful_transfers'];

// Calculate success rate
$success_rate = $total_transfers > 0 ? ($successful_transfers / $total_transfers) * 100 : 0;

// Fetch pending transfers with club details
$stmt = $conn->prepare("SELECT t.transfer_id, t.user_id, t.status, 
                               c_from.club_name AS from_club, 
                               c_to.club_name AS to_club 
                        FROM transfers t
                        JOIN clubs c_from ON t.transferred_from = c_from.club_id
                        JOIN clubs c_to ON t.transferred_to = c_to.club_id
                        WHERE t.agent_id = ? AND t.status = 'pending'");

$stmt->bind_param("i", $agent_id);
$stmt->execute();
$pending_transfers = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Transfers and Statistics</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Agent Transfers and Statistics</h1>
    <h2>Statistics</h2>
    <p>Total Transfers: <?php echo $total_transfers; ?></p>
    <p>Success Rate: <?php echo number_format($success_rate, 2); ?>%</p>

    <h2>Pending Transfers</h2>
    <div id="pendingTransfersTable">
        <?php
        if ($pending_transfers->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Transfer ID</th>
                        <th>User ID</th>
                        <th>From Club</th>
                        <th>To Club</th>
                        <th>Status</th>
                    </tr>";
            while ($row = $pending_transfers->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['transfer_id']}</td>
                        <td>{$row['user_id']}</td>
                        <td>{$row['from_club']}</td>
                        <td>{$row['to_club']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No pending transfers.";
        }
        ?>
    </div>
</body>
</html>
