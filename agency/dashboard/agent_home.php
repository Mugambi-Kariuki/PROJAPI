<?php
session_start();

if (!isset($_SESSION['agent_id'])) {
    header("Location: ../form/agent_login.php");
    exit();
}

require_once "../classes/database.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

$agent_id = $_SESSION['agent_id'];

// Handle booking status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id']) && isset($_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];
    $status = $action == 'accept' ? 'accepted' : 'rejected';

    $stmt = $conn->prepare("UPDATE bookings SET booking_status = ? WHERE booking_id = ? AND agent_id = ?");
    $stmt->bind_param("sii", $status, $booking_id, $agent_id);
    $stmt->execute();
}

// Fetch agent details
$stmt = $conn->prepare("SELECT full_name, contact_number, email, charge_fee, nationality FROM agents WHERE agent_id = ?");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$agent = $result->fetch_assoc();

// Fetch agent bookings with user details
$stmt = $conn->prepare("SELECT 
                            b.booking_id, 
                            u.name AS customer_name, 
                            b.target_country, 
                            b.years, 
                            b.expected_salary, 
                            b.created_at, 
                            b.booking_status 
                        FROM bookings b
                        JOIN users u ON b.user_id = u.id
                        WHERE b.agent_id = ?");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .navbar, .sidebar, .footer {
            background-color: #343a40;
            color: #ffffff;
        }
        .navbar a, .sidebar a, .footer a {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="toggleDarkMode">Toggle Dark Mode</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../processes/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex">
        <div class="sidebar bg-dark p-3">
            <h4>Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">My Profile</a>
                </li>
            </ul>
        </div>
        <div class="content p-4">
            <h1>Welcome, <?php echo htmlspecialchars($agent['full_name']); ?></h1>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Agent Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($agent['contact_number']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($agent['email']); ?></p>
                    <p><strong>Charge Fee:</strong> <?php echo htmlspecialchars($agent['charge_fee']); ?></p>
                    <p><strong>Nationality:</strong> <?php echo htmlspecialchars($agent['nationality']); ?></p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3>Bookings</h3>
                </div>
                <div class="card-body">
                    <?php if (count($bookings) > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer Name</th>
                                    <th>Target Country</th>
                                    <th>Years</th>
                                    <th>Expected Salary</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking['booking_id']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['target_country']); ?></td>
                                        <td><?php echo $booking['years']; ?></td>
                                        <td>$<?php echo number_format($booking['expected_salary']); ?></td>
                                        <td><?php echo $booking['created_at']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['booking_status']); ?></td>
                                        <td>
                                            <?php if ($booking['booking_status'] == 'pending'): ?>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                                </form>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                            <?php else: ?>
                                                <?php echo ucfirst($booking['booking_status']); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No bookings found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer bg-dark text-white mt-4 p-3">
        <div class="container">
            <p>Contact: +254740905321</p>
            <p>Email: goatsagency@gmail.com</p>
            <button class="btn btn-primary" onclick="scrollToFooter()">Feedback</button>
            <button class="btn btn-secondary" onclick="scrollToTop()">↑</button>
        </div>
    </footer>

    <script>
        document.getElementById('toggleDarkMode').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
        });

        function scrollToFooter() {
            document.querySelector('.footer').scrollIntoView({ behavior: 'smooth' });
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
