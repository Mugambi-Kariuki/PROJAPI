<?php
require_once "../classes/database.php";

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE bookings ADD COLUMN referred_agent_id INT DEFAULT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Column referred_agent_id added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?>
