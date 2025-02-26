<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

if (isset($_GET['club_id'])) {
    $club_id = $_GET['club_id'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Delete the club
        $stmt = $conn->prepare("DELETE FROM clubs WHERE club_id = ?");
        $stmt->bind_param("i", $club_id);

        if ($stmt->execute()) {
            header('Location: view_clubs.php');
        } else {
            throw new Exception("Failed to delete club: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        die("An error occurred: " . $e->getMessage());
    } finally {
        $conn->close(); // Ensure the connection is closed
    }
} else {
    die("Invalid request.");
}
?>
