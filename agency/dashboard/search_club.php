<?php
include '../classes/database.php'; // Ensure the correct path to your database connection file

if (!isset($_GET['q'])) {
    die("Search term not provided");
}

$search = trim($_GET['q']);

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Search for clubs by name
    $stmt = $conn->prepare("SELECT name FROM clubs WHERE name LIKE ? LIMIT 10");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($club = $result->fetch_assoc()) {
            echo "<div onclick='selectClub(\"" . addslashes($club['name']) . "\")'>" . htmlspecialchars($club['name']) . "</div>";
        }
    } else {
        echo "<div>No clubs found</div>";
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    $stmt->close();
    $conn->close();
}
?>
