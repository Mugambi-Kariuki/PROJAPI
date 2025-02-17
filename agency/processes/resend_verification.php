<?php
session_start();
require_once "../classes/database.php"; 
require "../vendor/autoload.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    error_log("Database connection failed.");
    die("Database connection failed.");
}
error_log("Database connection successful.");

// Fetch user email from session
$email = $_SESSION['email'];
$verification_code = rand(100000, 999999);

// Store new verification code in the database
$stmt = $conn->prepare("INSERT INTO user_verification (user_id, verification_code) 
                        VALUES (?, ?) ON DUPLICATE KEY UPDATE verification_code = ?");
if (!$stmt) {
    error_log("Prepare statement failed: " . $conn->error);
    die("Prepare statement failed: " . $conn->error);
}
$stmt->execute([$_SESSION['user_id'], $verification_code, $verification_code]);
error_log("Verification code stored successfully.");

// Log the verification code for debugging
error_log("Stored verification code: $verification_code for user_id: " . $_SESSION['user_id']);

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

// Redirect to verification page
header("Location: ../form/verification.php");
exit();
?>
