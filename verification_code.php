<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

// Database connection
$servername = "localhost:3308";
$username = "root"; 
$password = "caleb"; 
$dbname = "api_proj"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to generate OTP (6-digit random number)
function generateOTP($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= rand(0, 9); // Generate random digit
    }
    return $otp;
}

// Function to send OTP via email using PHPMailer
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use your SMTP server (Gmail in this case)
        $mail->SMTPAuth = true;
        $mail->Username = 'yballer110@gmail.com';  // SMTP username
        $mail->Password = 'viwgnqkfwjgthjsc';  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('caleb.kariuki@strathmore.edu', 'Your Name');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code for Verification';
        $mail->Body    = "Your OTP code is: <strong>$otp</strong><br>Please use this code to verify your account.";

        // Send email
        $mail->send();
        echo 'OTP has been sent to your email.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Example usage (you will need to pass user email dynamically)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; 
    $otp = generateOTP(6); // Generate 6-digit OTP

    // Store OTP in your database or session for later verification

    // Send OTP to user via email
    sendOTPEmail($email, $otp);
}
?>

<!-- Example form for user to request OTP -->
<form method="POST" action="verification_code.php">
    <label for="email">Enter your email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Send OTP</button>
</form>



?>