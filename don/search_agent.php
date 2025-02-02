<?php
require 'db.php';

$query = $_POST['query'];
$stmt = $conn->prepare("SELECT * FROM users u JOIN agents a ON u.id = a.user_id WHERE u.name LIKE ?");
$search = "%$query%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p>{$row['name']} - Success Rate: {$row['success_rate']}% - Fees: {$row['fees']}</p>";
}
?>
