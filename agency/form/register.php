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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Register</h2>
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
</body>
</html>
