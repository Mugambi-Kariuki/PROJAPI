<?php
session_start();
require_once "classes/database.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO footballers (user_id, name, age, club, position, nationality, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['name'],
        $_POST['age'],
        $_POST['club'],
        $_POST['position'],
        $_POST['nationality'],
        $_POST['salary']
    ]);

    header("Location: dashboard.php?profile_updated=1");
    exit();
}
?>
