<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

class Club {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function fetchAll() {
        $result = $this->conn->query("SELECT club_id, club_name, location FROM clubs");
        if (!$result) {
            throw new Exception("Failed to fetch clubs: " . $this->conn->error);
        }
        return $result;
    }
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $club = new Club($conn);
    $clubs = $club->fetchAll();
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
    <title>View Clubs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Club Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>All Clubs</h1>

        <form id="clubForm">
            <h2 class="mt-5">Add New Club</h2>
            <div class="form-inline">
                <div class="form-group mb-2">
                    <label for="club_name" class="sr-only">Name:</label>
                    <input type="text" id="club_name" name="club_name" class="form-control" placeholder="Name" required>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="location" class="sr-only">Location:</label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Location" required>
                </div>
                <button type="submit" class="btn btn-success mb-2">Add Club</button>
            </div>
        </form>

        <h2 class="mt-5">Search for a Club</h2>
        <div class="row">
            <div class="col-md-10">
                <input type="text" id="search" class="form-control" placeholder="Search for a club...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" onclick="searchClub()">Search</button>
            </div>
        </div>
        <div id="searchResults" class="mt-3"></div>

        <h2 class="mt-5">Club List</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Club ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($club = $clubs->fetch_assoc()): ?>
                    <tr class="table-light">
                        <td><?= $club['club_id'] ?></td>
                        <td><?= $club['club_name'] ?></td>
                        <td><?= $club['location'] ?></td>
                        <td>
                            <a href="edit_club.php?club_id=<?= $club['club_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <a href="delete_club.php?club_id=<?= $club['club_id'] ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        function searchClub() {
            var search = document.getElementById('search').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_club.php?search=' + encodeURIComponent(search), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        document.getElementById('searchResults').innerHTML = xhr.responseText;
                    } else {
                        document.getElementById('searchResults').innerHTML = 'An error occurred while searching for clubs.';
                        console.error('Error: ' + xhr.statusText);
                    }
                }
            };
            xhr.onerror = function () {
                document.getElementById('searchResults').innerHTML = 'An error occurred while searching for clubs.';
                console.error('Request failed');
            };
            xhr.send();
        }

        document.getElementById('clubForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_club.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('Club added successfully');
                    location.reload();
                }
            };
            xhr.send(formData);
        });

        document.getElementById('searchResults').addEventListener('click', function(event) {
            if (event.target && event.target.nodeName == "BUTTON") {
                document.getElementById('search').value = event.target.textContent;
            }
        });
    </script>
</body>
</html>
