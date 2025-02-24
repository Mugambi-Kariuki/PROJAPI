<?php
require_once '../classes/database.php';

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT name FROM clubs WHERE name LIKE ?");
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<div>" . htmlspecialchars($row['name']) . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
