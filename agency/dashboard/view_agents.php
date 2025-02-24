<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../form/login_admin.php');
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Fetch all agents
    $agents = $conn->query("SELECT * FROM agents");
    if (!$agents) {
        throw new Exception("Failed to fetch agents: " . $conn->error);
    }
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
    <title>View Agents</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="admin_dashboard.php">Home</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../processes/logout.php">
                        <i class="fas fa-lock"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>All Agents</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAgentModal">
            <i class="fas fa-plus"></i> Add Agent
        </button>
        <table class="table table-hover table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($agent = $agents->fetch_assoc()): ?>
                    <tr class="table-light">
                        <td><?= $agent['agent_id'] ?></td>
                        <td><?= $agent['full_name'] ?></td>
                        <td><?= $agent['email'] ?></td>
                        <td>
                            <a href="edit_agent.php?id=<?= $agent['agent_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <a href="delete_agent.php?id=<?= $agent['agent_id'] ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Agent Modal -->
    <div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgentModalLabel">Add New Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAgentForm">
                        <div class="mb-3">
                            <label for="agentName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="agentName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="agentEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="agentEmail" name="email" required>
                        </div>
                        <!-- Add additional fields as per your table schema -->
                        <div class="mb-3">
                            <label for="agentContact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="agentContact" name="contact_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="agentFee" class="form-label">Charge Fee</label>
                            <input type="number" step="0.01" class="form-control" id="agentFee" name="charge_fee" required>
                        </div>
                        <div class="mb-3">
                            <label for="agentNationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="agentNationality" name="nationality" required>
                        </div>
                        <div class="mb-3">
                            <label for="agentPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="agentPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('addAgentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('../processes/add_agent.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
