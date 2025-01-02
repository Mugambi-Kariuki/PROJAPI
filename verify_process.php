<?php
// Database configuration
$servername = "localhost:3308";
$username = "root";
$password = "caleb"; // Replace with your DB password
$dbname = "api_proj";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);

    // Fetch user data
    $stmt = $conn->prepare("SELECT verification_code, verification_expiry FROM Users WHERE email = ? AND is_verified = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($storedOtp, $expiry);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if ($otp == $storedOtp) {
            if (strtotime($expiry) > time()) {
                // Update verification status
                $updateStmt = $conn->prepare("UPDATE Users SET is_verified = 1, verification_code = NULL, verification_expiry = NULL WHERE email = ?");
                $updateStmt->bind_param("s", $email);
                if ($updateStmt->execute()) {
                    echo "Account verified successfully! <a href='login.php'>Login here</a>.";
                } else {
                    echo "Error updating verification status.";
                }
                $updateStmt->close();
            } else {
                echo "Verification code has expired.";
            }
        } else {
            echo "Invalid verification code.";
        }
    } else {
        echo "No account found or already verified.";
    }

    $stmt->close();
}

$conn->close();
?>
