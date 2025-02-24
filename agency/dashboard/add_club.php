<?php
include '../classes/database.php'; // Update the path to your database connection file

if (!isset($_POST['club_name']) || !isset($_POST['location'])) {
    die("Name and location are required");
}

$club_name = $_POST['club_name'];
$location = $_POST['location'];

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Add new club
    $stmt = $conn->prepare("INSERT INTO clubs (club_name, location) VALUES (?, ?)");
    $stmt->bind_param("ss", $club_name, $location);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Club added successfully";
    } else {
        throw new Exception("Failed to add club");
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
    <title>Add Club</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="admin_dashboard.php">Home</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../processes/logout.php">
                        <i class="fas fa-lock"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>Add New Club</h1>
        <form id="addClubForm">
            <div class="form-group mb-2">
                <label for="club_name" class="sr-only">Name:</label>
                <input type="text" id="club_name" name="club_name" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-group mb-2">
                <label for="location" class="sr-only">Location:</label>
                <input type="text" id="location" name="location" class="form-control" placeholder="Location" required>
            </div>
            <button type="submit" class="btn btn-success mb-2">Add Club</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('addClubForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_club.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('Club added successfully');
                    window.location.href = 'view_clubs.php';
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>
