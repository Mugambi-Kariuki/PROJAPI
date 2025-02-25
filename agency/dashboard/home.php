<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../form/login.php");
    exit();
}

require_once "../classes/database.php";
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch user details from footballers table
$stmt = $conn->prepare("SELECT name, age, club, position, nationality, salary FROM footballers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body.dark-mode {
            background-color: #121212;
            color: white;
        }
        .card.dark-mode {
            background-color: #1e1e1e;
            color: white;
        }
        /* Sidebar styles */
        #sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background: white;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            transition: right 0.3s ease;
        }
        #sidebar.show {
            right: 0;
        }
        #closeSidebar {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: none;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
    <script>
        $(document).ready(function(){
            $("#search").keyup(function(){
                var query = $(this).val();
                if (query != "") {
                    $.ajax({
                        url: 'search_agents.php',
                        method: 'POST',
                        data: {query: query},
                        success: function(data) {
                            $('#agentTable').html(data);
                        }
                    });
                } else {
                    $('#agentTable').html('');
                }
            });

            $("#toggleDarkMode").click(function() {
                $("body").toggleClass("dark-mode");
                $(".card").toggleClass("dark-mode");
            });

            $("#openSidebar").click(function() {
                $("#sidebar").addClass("show");
            });

            $("#closeSidebar").click(function() {
                $("#sidebar").removeClass("show");
            });
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">THEE GOATs FOOTY AGENCY</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button id="toggleDarkMode" class="btn btn-secondary mr-2">Toggle Dark Mode</button>
                </li>
                <li class="nav-item">
                    <button id="openSidebar" class="btn btn-info mr-2">My Profile</button>
                </li>
                <li class="nav-item">
                    <a href="../processes/logout.php" class="btn btn-danger">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Agents</h1>
        <input type="text" id="search" class="form-control mb-4" placeholder="Search agents...">
        <div id="agentTable" class="row">
            <?php
            $stmt = $conn->prepare("SELECT * FROM agents");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusColor = isset($row['status']) && $row['status'] == 'Occupied' ? 'red' : 'green';
                    $statusText = isset($row['status']) && $row['status'] == 'Occupied' ? "Sorry, I'm busy. Try again later!" : 'Free';
                    $successRate = isset($row['success_rate']) ? $row['success_rate'] : 'N/A';
                    $bookLink = isset($row['status']) && $row['status'] == 'Occupied' ? 'Sorry, am busy, check me out later' : "
                        <a href='book_agent.php?agent_id={$row['agent_id']}' class='btn btn-primary'>
                            Book Agent
                        </a>";
                    echo "<div class='col-md-4 mb-4'>
                            <div class='card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>{$row['full_name']}</h5>
                                    <p class='card-text'>
                                        <strong>Contact Number:</strong> {$row['contact_number']}<br>
                                        <strong>Email:</strong> {$row['email']}<br>
                                        <strong>Status:</strong> <span style='color: $statusColor;'>$statusText</span><br>
                                        <strong>Charge Fee:</strong> {$row['charge_fee']}<br>
                                        <strong>Nationality:</strong> {$row['nationality']}<br>
                                        <strong>Success Rate:</strong> $successRate
                                    </p>
                                    $bookLink
                                </div>
                            </div>
                          </div>";
                }
            } else {
                echo "No records found.";
            }
            ?>
        </div>
    </div>

    <!-- Sidebar (Profile Pop-up) -->
    <div id="sidebar">
        <button id="closeSidebar">&times;</button>
        <h3>My Profile</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
        <p><strong>Club:</strong> <?php echo htmlspecialchars($user['club']); ?></p>
        <p><strong>Position:</strong> <?php echo htmlspecialchars($user['position']); ?></p>
        <p><strong>Nationality:</strong> <?php echo htmlspecialchars($user['nationality']); ?></p>
        <p><strong>Salary:</strong> <?php echo htmlspecialchars($user['salary']); ?></p>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
