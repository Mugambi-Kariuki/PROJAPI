<?php
require_once "../classes/database.php";
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM agents WHERE full_name LIKE ?");
    $search = "%$query%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Agent ID</th>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Charge Fee</th>
                    <th>Nationality</th>
                    <th>Success Rate</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            $statusColor = $row['status'] == 'Occupied' ? 'red' : 'green';
            echo "<tr>
                    <td>{$row['agent_id']}</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['contact_number']}</td>
                    <td>{$row['email']}</td>
                    <td style='color: $statusColor;'>{$row['status']}</td>
                    <td>{$row['charge_fee']}</td>
                    <td>{$row['nationality']}</td>
                    <td>{$row['success_rate']}</td>
                    <td>{$row['created_at']}</td>
                    <td><a href='book_agent.php?agent_id={$row['agent_id']}'>Book Agent</a></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }
}
?>
