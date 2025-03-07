<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

class Player {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function delete($player_id) {
        $stmt = $this->conn->prepare("DELETE FROM footballers WHERE id = ?");
        $stmt->bind_param("i", $player_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to delete player: " . $this->conn->error);
        }
    }
}

if (isset($_GET['id'])) {
    $player_id = $_GET['id'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $player = new Player($conn);
        $player->delete($player_id);

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
