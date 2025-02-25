<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

if (isset($_GET['id'])) {
    $player_id = $_GET['id'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Delete player from all affiliated records
        $deletePlayer = $conn->prepare("DELETE FROM footballers WHERE id = ?");
        $deletePlayer->bind_param("i", $player_id);

        if (!$deletePlayer->execute()) {
            throw new Exception("Failed to delete player: " . $conn->error);
        }

        header('Location: view_players.php');
        exit();
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("An error occurred: " . $e->getMessage());
    } finally {
        $conn->close(); // Ensure the connection is closed
    }
} else {
    header('Location: view_players.php');
    exit();
}
?>
