<?php
include '../classes/database.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../form/login_user.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = $_POST['agent_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO bookings (agent_id, date, time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $agent_id, $date, $time);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header('Location: user_dashboard.php');
            exit();
        } else {
            throw new Exception("Failed to book agent");
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("An error occurred: " . $e->getMessage());
    } finally {
        $conn->close();
    }
} else {
    header('Location: book_agent.php');
    exit();
}
?>
