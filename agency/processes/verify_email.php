<?php
require_once "../classes/database.php"; // Correct the path to the Database class file

// Get the verification code from the URL
$verification_code = $_GET['code'];
error_log("Verification code received: $verification_code");

// database connection instance
$db = new Database();
$conn = $db->getConnection();

// Verify the code
$stmt = $conn->prepare("SELECT user_id FROM user_verification WHERE verification_code = ?");
$stmt->bind_param("s", $verification_code);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update the user's status to verified
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
