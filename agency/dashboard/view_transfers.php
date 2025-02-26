<?php
include '../classes/database.php'; // Update the path to your database connection file
include 'template.php'; // Include the template file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

class Transfer {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function fetchAll() {
        $result = $this->conn->query("SELECT * FROM transfers");
        if (!$result) {
            throw new Exception("Failed to fetch transfers: " . $this->conn->error);
        }
        return $result;
    }
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $transfer = new Transfer($conn);
    $transfers = $transfer->fetchAll();
} catch (Exception $e) {
    error_log($e->getMessage());
    die("An error occurred: " . $e->getMessage());
} finally {
    $conn->close(); // Ensure the connection is closed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Transfers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="container">
        <h1>All Transfers</h1>
        <table class="table table-hover table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Player Name</th>
                    <th>From Club</th>
                    <th>To Club</th>
                    <th>Transfer Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transfer = $transfers->fetch_assoc()): ?>
                    <tr class="table-light">
                        <td><?= $transfer['id'] ?></td>
                        <td><?= $transfer['player_name'] ?></td>
                        <td><?= $transfer['from_club'] ?></td>
                        <td><?= $transfer['to_club'] ?></td>
                        <td><?= $transfer['transfer_date'] ?></td>
                        <td>
                            <a href="edit_transfer.php?id=<?= $transfer['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <a href="delete_transfer.php?id=<?= $transfer['id'] ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
