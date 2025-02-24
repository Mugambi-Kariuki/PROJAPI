<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../form/login.php");
    exit();
}

require_once "../classes/database.php";
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

$agent_id = $_GET['agent_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Agent</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Book Agent</h1>
        <form action="submit_booking.php" method="POST">
            <input type="hidden" name="agent_id" value="<?php echo $agent_id; ?>">
            <div class="form-group">
                <label for="target_country">Target Country:</label>
                <input type="text" class="form-control" id="target_country" name="target_country" required>
            </div>
            <div class="form-group">
                <label for="years">Number of Years (max 5):</label>
                <input type="number" class="form-control" id="years" name="years" max="5" required>
            </div>
            <div class="form-group">
                <label for="expected_salary">Expected Salary at New Club:</label>
                <input type="number" class="form-control" id="expected_salary" name="expected_salary" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
