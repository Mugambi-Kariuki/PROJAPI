<?php
session_start();
require_once "../classes/database.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'C:/Apache24/htdocs/projApi/error_log.txt');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $verification_code = rand(100000, 999999); // Generate a random verification code

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        error_log("Database connection failed.");
        die("Database connection failed.");
    }
    error_log("Database connection successful.");

    // Insert user data
    $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $password);
    if (!$stmt->execute()) {
        error_log("Execute statement failed: " . $stmt->error);
        die("Execute statement failed: " . $stmt->error);
    }
    error_log("User data inserted successfully.");

    $user_id = $stmt->insert_id;

    // Insert verification code
    $stmt = $conn->prepare("INSERT INTO user_verification (user_id, verification_code) VALUES (?, ?)");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("is", $user_id, $verification_code);
    if (!$stmt->execute()) {
        error_log("Execute statement failed: " . $stmt->error);
        die("Execute statement failed: " . $stmt->error);
    }
    error_log("Verification code inserted successfully.");

    // Log successful insertion
    error_log("Inserted verification code $verification_code for user_id: $user_id");

    // Redirect to verification page
    $_SESSION['user_id'] = $user_id;
    header("Location: verification.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        .register-form {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            margin: 0 auto;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
                <li class="nav-item">
                    <a class="nav-link" href="../form/admin_login.php">Admin Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../form/agent_login.php">Agent Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex flex-column min-vh-100">
        <div class="d-flex">
            <div class="sidebar bg-dark p-3">
                <h4>Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Profile</a>
                    </li>
                </ul>
            </div>
            <div class="content p-4">
                <h1>Register</h1>
                <div class="register-form">
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer bg-dark text-white mt-4 p-3">
            <div class="container">
                <p>Contact: +254740905321</p>
                <p>Email: goatsagency@gmail.com</p>
                <button class="btn btn-primary" onclick="scrollToFooter()">Feedback</button>
                <button class="btn btn-secondary" onclick="scrollToTop()">↑</button>
            </div>
        </footer>
    </div>

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
    </script>
</body>
</html>
