<?php
include '../classes/database.php'; // Update the path to your database connection file

if (!isset($_GET['search'])) {
    die("Search term not provided");
}

$search = $_GET['search'];

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Search for clubs
    $stmt = $conn->prepare("SELECT * FROM clubs WHERE name LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='table table-hover table-striped table-bordered'>
                <thead class='table-dark'>
                    <tr>
                        <th>Club ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
        while ($club = $result->fetch_assoc()) {
            echo "<tr class='table-light'>
                    <td>{$club['club_id']}</td>
                    <td>{$club['name']}</td>
                    <td>{$club['location']}</td>
                    <td>
                        <a href='edit_club.php?club_id={$club['club_id']}' class='btn btn-warning btn-sm'>
                            <i class='fas fa-pen'></i> Edit
                        </a>
                        <a href='delete_club.php?club_id={$club['club_id']}' class='btn btn-danger btn-sm'>
                            <i class='fas fa-trash'></i> Delete
                        </a>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No clubs found";
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    $conn->close(); // Ensure the connection is closed
}
?>
