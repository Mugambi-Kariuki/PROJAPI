<?php
session_start();
require_once "../classes/database.php";
require "../vendor/autoload.php"; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'C:/Apache24/htdocs/projApi/error_log.txt');

// Debugging: Print session data
/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['code'])) {
        $code = trim($_POST['code']);
    } else {
        $code = '';
    }
    $user_id = $_SESSION['user_id'];

    error_log("Received verification code: $code for user_id: $user_id");

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        error_log("Database connection failed.");
        die("Database connection failed.");
    }
    error_log("Database connection successful.");

    // Check if the code matches
    $stmt = $conn->prepare("SELECT * FROM user_verification WHERE user_id = ? AND verification_code = ?");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("is", $user_id, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        error_log("Verification code matched for user_id: $user_id");

        // Mark as verified
        $stmt = $conn->prepare("UPDATE user SET is_verified = 1 WHERE id = ?");
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            die("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        error_log("User marked as verified.");

        // Delete verification code
        $stmt = $conn->prepare("DELETE FROM user_verification WHERE user_id = ?");
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            die("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        error_log("Verification code deleted.");

        // Log redirection
        error_log("Redirecting to ../form/login.php");
        header("Location: ../form/login.php");
        exit();
    } else {
        error_log("Invalid verification code for user_id: $user_id");
        echo "Invalid verification code.";
    }
}

// Send verification code email if requested
if (isset($_POST['resend'])) {
    $email = $_SESSION['email'];
    $verification_code = rand(100000, 999999);

    // Store new verification code in the database
    $stmt = $conn->prepare("INSERT INTO user_verification (user_id, verification_code) 
                            VALUES (?, ?) ON DUPLICATE KEY UPDATE verification_code = ?");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("iis", $user_id, $verification_code, $verification_code);
    if (!$stmt->execute()) {
        error_log("Execute statement failed: " . $stmt->error);
        die("Execute statement failed: " . $stmt->error);
    }
    error_log("Verification code stored successfully.");

    // Send verification email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; 
        $mail->SMTPAuth = true;
        $mail->Username = "caleb.kariuki@strathmore.edu";
        $mail->Password = "vbfbayghqizgwxux";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->setFrom('caleb.kariuki@strathmore.edu', 'Footy Agency');
        $mail->addAddress($email);
        $mail->Subject = "Your New Verification Code";
        $mail->Body = "Your new verification code is: $verification_code";

        $mail->send();
        error_log("Sent verification code: $verification_code to email: $email");
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        die("Failed to send email.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="verificationForm" method="post">
        <input type="text" name="code" placeholder="Enter Verification Code" required>
        <button type="submit">Verify</button>
    </form>
    <form action="verification.php" method="POST">
        <button type="submit" name="resend">Resend Verification Code</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#verificationForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way
                $.ajax({
                    type: 'POST',
                    url: 'verification.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.includes('Invalid verification code')) {
                            alert('Invalid verification code. Please try again.');
                        } else {
                            window.location.href = '../form/login.php'; // Redirect to login page
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
