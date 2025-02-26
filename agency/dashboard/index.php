<?php
include '../classes/database.php'; // Update the path to your database connection file

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

    // Fetch all tables
    $tables = $conn->query("SHOW TABLES");
    if (!$tables) {
        throw new Exception("Failed to fetch tables: " . $conn->error);
    }
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
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="view_clubs.php">View Clubs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>All Tables</h1>
        <div class="list-group">
            <?php while ($table = $tables->fetch_array()): ?>
                <button class="list-group-item list-group-item-action" onclick="showTableData('<?= $table[0] ?>')"><?= $table[0] ?></button>
            <?php endwhile; ?>
        </div>
        <div id="tableData" class="mt-5"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        function showTableData(tableName) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'show_table_data.php?table=' + encodeURIComponent(tableName), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('tableData').innerHTML = xhr.responseText;
                } else if (xhr.readyState == 4) {
                    document.getElementById('tableData').innerHTML = 'An error occurred while fetching table data.';
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
