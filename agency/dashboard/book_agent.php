<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../form/login_user.php');
    exit();
}

if (!isset($_GET['agent_id'])) {
    die("Agent ID not provided");
}

$agent_id = $_GET['agent_id'];

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Fetch agent details
    $stmt = $conn->prepare("SELECT * FROM agents WHERE agent_id = ?");
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        throw new Exception("Agent not found");
    }

    $agent = $result->fetch_assoc();
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
    <title>Book Agent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .navbar, .sidebar, .footer {
            background-color: #343a40;
            color: #ffffff;
        }
        .navbar a, .sidebar a, .footer a {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="toggleDarkMode">Toggle Dark Mode</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Book Agent: <?= $agent['name'] ?></h1>
        <form id="bookAgentForm">
            <div class="form-group mb-2">
                <label for="date" class="sr-only">Date:</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label for="time" class="sr-only">Time:</label>
                <input type="time" id="time" name="time" class="form-control" required>
            </div>
            <input type="hidden" name="agent_id" value="<?= $agent['agent_id'] ?>">
            <button type="submit" class="btn btn-primary">Book Agent</button>
        </form>

        <h2>Potential Clubs</h2>
        <div id="clubsTable">
            <?php
            $stmt = $conn->prepare("SELECT * FROM clubs");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table border='1'>
                        <tr>
                            <th>Club ID</th>
                            <th>Club Name</th>
                            <th>Location</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['club_id']}</td>
                            <td>{$row['club_name']}</td>
                            <td>{$row['location']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "No records found.";
            }
            ?>
        </div>

        <h2>Available Playing Positions</h2>
        <div id="positionsTable">
            <?php
            $stmt = $conn->prepare("SELECT * FROM positions");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table border='1'>
                        <tr>
                            <th>Position ID</th>
                            <th>Position Name</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['position_id']}</td>
                            <td>{$row['position_name']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "No records found.";
            }
            ?>
        </div>

        <h2>Add Agent</h2>
        <form method="POST">
            <input type="text" name="agent_name" placeholder="Agent Name" required>
            <button type="submit" name="add_agent">Add Agent</button>
        </form>

        <h2>Add Club</h2>
        <form method="POST">
            <input type="text" name="club_name" placeholder="Club Name" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit" name="add_club">Add Club</button>
        </form>

        <h2>Update Agent Status</h2>
        <form method="POST">
            <input type="number" name="agent_id" placeholder="Agent ID" required>
            <input type="text" name="status" placeholder="Status" required>
            <button type="submit" name="update_agent_status">Update Status</button>
        </form>

        <h2>Update Footballer Club</h2>
        <form method="POST">
            <input type="number" name="footballer_id" placeholder="Footballer ID" required>
            <input type="number" name="club_id" placeholder="Club ID" required>
            <button type="submit" name="update_footballer_club">Update Club</button>
        </form>
    </div>

    <footer class="footer bg-dark text-white mt-4 p-3">
        <div class="container">
            <p>Contact: +254740905321</p>
            <p>Email: goatsagency@gmail.com</p>
            <button class="btn btn-primary" onclick="scrollToFooter()">Feedback</button>
            <button class="btn btn-secondary" onclick="scrollToTop()">↑</button>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('toggleDarkMode').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
        });

        function scrollToFooter() {
            document.querySelector('.footer').scrollIntoView({ behavior: 'smooth' });
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('bookAgentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'book_agent_action.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('Agent booked successfully');
                    window.location.href = 'user_dashboard.php';
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>
