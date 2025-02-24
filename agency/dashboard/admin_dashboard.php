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
</head>
<body>
    <div class="container">
        <h2>Summary</h2>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
