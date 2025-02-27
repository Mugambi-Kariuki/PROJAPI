<?php/*
session_start();
require_once "../classes/database.php"; // Ensure the correct path

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'C:/Apache24/htdocs/projApi/error_log.txt');

$db = new Database();
$conn = $db->getConnection();

// Debugging: Check database connection
if (!$conn) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
    if ($stmt === false) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute() === false) {
        error_log("Execute statement failed: " . $stmt->error);
        die("Execute failed: " . $stmt->error);
    }

    // Set session user_id
    $_SESSION['user_id'] = $stmt->insert_id;
    error_log("User signed up with ID: " . $_SESSION['user_id']);

    header("Location: ../dashboard/home.php");
    exit();
}*/
?>
