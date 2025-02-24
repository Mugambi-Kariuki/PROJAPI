<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

if (!isset($_GET['club_id'])) {
    die("Club ID not provided");
}

$club_id = $_GET['club_id'];

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Fetch club details
    $stmt = $conn->prepare("SELECT * FROM clubs WHERE club_id = ?");
    $stmt->bind_param("i", $club_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        throw new Exception("Club not found");
    }

    $club = $result->fetch_assoc();
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
    <title>Edit Club</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="container">
        <h1>Edit Club</h1>
        <form id="editClubForm">
            <div class="form-group mb-2">
                <label for="club_name" class="sr-only">Name:</label>
                <input type="text" id="club_name" name="club_name" class="form-control" value="<?= $club['club_name'] ?>" required>
            </div>
            <div class="form-group mb-2">
                <label for="location" class="sr-only">Location:</label>
                <input type="text" id="location" name="location" class="form-control" value="<?= $club['location'] ?>" required>
            </div>
            <input type="hidden" name="club_id" value="<?= $club['club_id'] ?>">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('editClubForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_club.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('Club updated successfully');
                    window.location.href = 'view_clubs.php';
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>
