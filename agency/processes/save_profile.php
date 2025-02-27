<?php
session_start();
require_once "../classes/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('log_errors', 1);
ini_set('error_log', 'C:/Apache24/htdocs/projApi/error_log.txt');

if (!isset($_SESSION['user_id'])) {
    error_log("Unauthorized access attempt.");
    die("Unauthorized access");
}

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
error_log("User ID from session: $user_id");

$stmt = $conn->prepare("SELECT id FROM user WHERE id = ?");
if ($stmt === false) {
    error_log("Prepare statement failed: " . $conn->error);
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    error_log("User ID $user_id does not exist in the user table.");
    die("User ID does not exist.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO footballers (user_id, name, age, club, position, nationality, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isisssd", $user_id, $_POST['name'], $_POST['age'], $_POST['club'], $_POST['position'], $_POST['nationality'], $_POST['salary']);
    if ($stmt->execute() === false) {
        error_log("Execute statement failed: " . $stmt->error);
        die("Execute failed: " . $stmt->error);
    }

    header("Location: ../dashboard/home.php?profile_updated=1");
    exit();
}
?>
