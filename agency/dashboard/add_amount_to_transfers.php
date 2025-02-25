<?php
require_once "../classes/database.php";

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE transfers ADD COLUMN amount DECIMAL(10,2) NOT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Column amount added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?>
