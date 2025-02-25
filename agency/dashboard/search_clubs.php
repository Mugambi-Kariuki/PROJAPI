<?php
require_once '../classes/database.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $query = "SELECT name FROM clubs WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $clubs = [];
    while ($row = $result->fetch_assoc()) {
        $clubs[] = $row;
    }
    echo json_encode($clubs);
}
?>
