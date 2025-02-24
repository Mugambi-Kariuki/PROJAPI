<?php
require_once '../classes/database.php';

if (isset($_GET['club'])) {
    $club = $_GET['club'];
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM clubs WHERE name = ?");
    $stmt->bind_param("s", $club);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "valid";
    } else {
        echo "invalid";
    }

    $stmt->close();
    $conn->close();
}
?>
