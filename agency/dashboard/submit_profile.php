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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $club = $_POST['club'];
    $position = $_POST['position'];
    $nationality = $_POST['nationality'];
    $salary = $_POST['salary'];

    $stmt = $conn->prepare("INSERT INTO footballers (user_id, name, age, club, position, nationality, salary) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssd", $_SESSION['user_id'], $name, $age, $club, $position, $nationality, $salary);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
