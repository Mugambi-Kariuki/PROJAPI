<?php
require_once "../classes/database.php"; 

$verification_code = $_GET['code'];
error_log("Verification code received: $verification_code");

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT user_id FROM user_verification WHERE verification_code = ?");
$stmt->bind_param("s", $verification_code);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE user SET is_verified = 1 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    echo "Email verified successfully!";
} else {
    error_log("Invalid verification code: $verification_code");
    echo "Invalid verification code.";
}

$conn->close();
?>
