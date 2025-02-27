<?php
include '../classes/database.php'; 

class Club {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function search($search) {
        $stmt = $this->conn->prepare("SELECT club_id, club_name, location FROM clubs WHERE club_name LIKE ?");
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $club = new Club($conn);
        $result = $club->search($search);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<button class='btn btn-outline-secondary mt-2'>" . htmlspecialchars($row['club_name']) . "</button>";
            }
        } else {
            echo "No clubs found.";
        }

        $conn->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "An error occurred: " . $e->getMessage();
    }
} else {
    echo "Invalid search query.";
}
?>
