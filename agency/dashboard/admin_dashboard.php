<?php
include '../classes/database.php'; // Update the path to your database connection file
include 'template.php'; // Include the template file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Fetch total number of users
    $result = $conn->query("SELECT COUNT(*) AS total FROM users");
    if (!$result) {
        throw new Exception("Failed to fetch total users: " . $conn->error);
    }
    $totalUsers = $result->fetch_assoc()['total'];
    
    // Fetch total number of agents
    $result = $conn->query("SELECT COUNT(*) AS total FROM agents");
    if (!$result) {
        throw new Exception("Failed to fetch total agents: " . $conn->error);
    }
    $totalAgents = $result->fetch_assoc()['total'];

    // Fetch total number of players
    $result = $conn->query("SELECT COUNT(*) AS total FROM footballers");
    if (!$result) {
        throw new Exception("Failed to fetch total players: " . $conn->error);
    }
    $totalPlayers = $result->fetch_assoc()['total'];

        // Fetch total number of bookings
    $result = $conn->query("SELECT COUNT(*) AS total FROM bookings");
    if (!$result) {
        throw new Exception("Failed to fetch total bookings: " . $conn->error);
    }
    $totalBookings = $result->fetch_assoc()['total'];

    // Fetch total number of clubs
    $result = $conn->query("SELECT COUNT(*) AS total FROM clubs");
    if (!$result) {
        throw new Exception("Failed to fetch total clubs: " . $conn->error);
    }
    $totalClubs = $result->fetch_assoc()['total'];

    // Fetch total number of transfers
    $result = $conn->query("SELECT COUNT(*) AS total FROM transfers");
    if (!$result) {
        throw new Exception("Failed to fetch total transfers: " . $conn->error);
    }
    $totalTransfers = $result->fetch_assoc()['total'];
} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    $conn->close(); // Ensure the connection is closed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h2>Summary</h2>
        <canvas id="usersChart"></canvas>
        <canvas id="agentsChart"></canvas>
        <canvas id="playersChart"></canvas>
        <canvas id="bookingsChart"></canvas>
        <canvas id="clubsChart"></canvas>
        <canvas id="transfersChart"></canvas>
        <p>Total Users: <?= $totalUsers ?></p>
        <p>Total Agents: <?= $totalAgents ?></p>
        <p>Total Players: <?= $totalPlayers ?></p>
        <p>Total Bookings: <?= $totalBookings ?></p>
        <p>Total Clubs: <?= $totalClubs ?></p>
        <p>Total Transfers: <?= $totalTransfers ?></p>

        <h2>Details</h2>
        <button class="btn btn-primary" onclick="location.href='view_users.php'">View Users</button>
        <button class="btn btn-primary" onclick="location.href='view_agents.php'">View Agents</button>
        <button class="btn btn-primary" onclick="location.href='view_players.php'">View Players</button>
        <button class="btn btn-primary" onclick="location.href='view_bookings.php'">View Bookings</button>
        <button class="btn btn-primary" onclick="location.href='view_clubs.php'">View Clubs</button>
        <button class="btn btn-primary" onclick="location.href='view_transfers.php'">View Transfers</button>
    </div>

    <script>
        const usersData = <?= $totalUsers ?>;
        const agentsData = <?= $totalAgents ?>;
        const playersData = <?= $totalPlayers ?>;
        const bookingsData = <?= $totalBookings ?>;
        const clubsData = <?= $totalClubs ?>;
        const transfersData = <?= $totalTransfers ?>;

        const ctxUsers = document.getElementById('usersChart').getContext('2d');
        const ctxAgents = document.getElementById('agentsChart').getContext('2d');
        const ctxPlayers = document.getElementById('playersChart').getContext('2d');
        const ctxBookings = document.getElementById('bookingsChart').getContext('2d');
        const ctxClubs = document.getElementById('clubsChart').getContext('2d');
        const ctxTransfers = document.getElementById('transfersChart').getContext('2d');

        new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: ['Users'],
                datasets: [{
                    label: 'Total Users',
                    data: [usersData],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(ctxAgents, {
            type: 'bar',
            data: {
                labels: ['Agents'],
                datasets: [{
                    label: 'Total Agents',
                    data: [agentsData],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(ctxPlayers, {
            type: 'bar',
            data: {
                labels: ['Players'],
                datasets: [{
                    label: 'Total Players',
                    data: [playersData],
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(ctxBookings, {
            type: 'bar',
            data: {
                labels: ['Bookings'],
                datasets: [{
                    label: 'Total Bookings',
                    data: [bookingsData],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(ctxClubs, {
            type: 'bar',
            data: {
                labels: ['Clubs'],
                datasets: [{
                    label: 'Total Clubs',
                    data: [clubsData],
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(ctxTransfers, {
            type: 'bar',
            data: {
                labels: ['Transfers'],
                datasets: [{
                    label: 'Total Transfers',
                    data: [transfersData],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
