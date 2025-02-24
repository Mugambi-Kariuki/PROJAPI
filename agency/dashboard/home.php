<?php
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
                    <button id="toggleDarkMode" class="btn btn-secondary">Toggle Dark Mode</button>
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
                    $statusColor = $row['status'] == 'Occupied' ? 'red' : 'green';
                    $bookLink = $row['status'] == 'Occupied' ? 'Sorry, am busy, check me out later' : "
                        <a href='book_agent_form.php?agent_id={$row['agent_id']}' class='btn btn-primary'>
                            Book Agent
                        </a>";
                    echo "<div class='col-md-4 mb-4'>
                            <div class='card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>{$row['full_name']}</h5>
                                    <p class='card-text'>
                                        <strong>Contact Number:</strong> {$row['contact_number']}<br>
                                        <strong>Email:</strong> {$row['email']}<br>
                                        <strong>Status:</strong> <span style='color: $statusColor;'>{$row['status']}</span><br>
                                        <strong>Charge Fee:</strong> {$row['charge_fee']}<br>
                                        <strong>Nationality:</strong> {$row['nationality']}<br>
                                        <strong>Success Rate:</strong> {$row['success_rate']}
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
