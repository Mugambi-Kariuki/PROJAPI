<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'C:/Apache24/htdocs/projApi/error_log.txt');

// Debugging: Print session data
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (!isset($_SESSION['user_id'])) {
    error_log("User not logged in, redirecting to login.php");
    header("Location: ../form/login.php");
    exit();
}

require_once "../classes/database.php";
$db = new Database();
$conn = $db->getConnection();

// Check database connection
if (!$conn) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful.";
}

$stmt = $conn->prepare("SELECT is_verified FROM user WHERE id = ?");
if (!$stmt) {
    error_log("Prepare statement failed: " . $conn->error);
    die("Prepare statement failed: " . $conn->error);
}
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['is_verified'] == 0) {
    error_log("User not verified, redirecting to verification.php");
    header("Location: ../form/verification.php");
    exit();
}

error_log("User verified, displaying dashboard");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Welcome to your dashboard!</h1>
    <a href="#" id="openProfile">Profile</a>
    <a href="../processes/logout.php">Logout</a>

    <?php include "profile_form.php"; ?>

</body>
</html>
