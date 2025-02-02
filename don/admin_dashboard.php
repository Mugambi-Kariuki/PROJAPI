<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require 'db.php';

// Fetch agent performance data
$agents = $conn->query("SELECT u.name, a.success_rate, a.transfers_done, a.failed_transfers FROM agents a JOIN users u ON a.user_id = u.id");

// Prepare data for Chart.js
$agent_names = [];
$success_rates = [];
$transfers_done = [];
$failed_transfers = [];

while ($agent = $agents->fetch_assoc()) {
    $agent_names[] = $agent['name'];
    $success_rates[] = $agent['success_rate'];
    $transfers_done[] = $agent['transfers_done'];
    $failed_transfers[] = $agent['failed_transfers'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Admin Analytics Dashboard</h2>

    <div class="row">
        <div class="col-md-6">
            <canvas id="successRateChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="transfersChart"></canvas>
        </div>
    </div>
</div>

<script>
const agentNames = <?php echo json_encode($agent_names); ?>;
const successRates = <?php echo json_encode($success_rates); ?>;
const transfersDone = <?php echo json_encode($transfers_done); ?>;
const failedTransfers = <?php echo json_encode($failed_transfers); ?>;

// Success Rate Chart
new Chart(document.getElementById("successRateChart"), {
    type: "bar",
    data: {
        labels: agentNames,
        datasets: [{
            label: "Success Rate (%)",
            data: successRates,
            backgroundColor: "rgba(75, 192, 192, 0.6)",
            borderColor: "rgba(75, 192, 192, 1)",
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});

// Transfers Chart
new Chart(document.getElementById("transfersChart"), {
    type: "bar",
    data: {
        labels: agentNames,
        datasets: [
            {
                label: "Transfers Done",
                data: transfersDone,
                backgroundColor: "rgba(54, 162, 235, 0.6)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1
            },
            {
                label: "Failed Transfers",
                data: failedTransfers,
                backgroundColor: "rgba(255, 99, 132, 0.6)",
                borderColor: "rgba(255, 99, 132, 1)",
                borderWidth: 1
            }
        ]
    },
    options: { responsive: true }
});
</script>
</body>
</html>
