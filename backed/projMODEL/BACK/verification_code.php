<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'dcConnection.php'; // Include your database connection file

// Function to generate a random verification code
function generateVerificationCode($length = 6) {
    return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
}

// Function to save the verification code to the database
function saveVerificationCodeToDatabase($email, $verificationCode, $conn) {
    $stmt = $conn->prepare("INSERT INTO user_verifications (email, verification_code, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $verificationCode);
    $stmt->execute();
    $stmt->close();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user-provided email and name
    $userEmail = $_POST['email'];
    $userName = $_POST['name'];

    // Generate a verification code
    $verificationCode = generateVerificationCode();

    // Save the verification code to the database
    saveVerificationCodeToDatabase($userEmail, $verificationCode, $conn);

    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debug output for production
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@gmail.com'; // Your email
        $mail->Password   = 'your_app_password';   // Your app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('no-reply@example.com', 'Verification Team');
        $mail->addAddress($userEmail, $userName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body    = "
            <p>Dear $userName,</p>
            <p>Thank you for signing up. Your verification code is:</p>
            <h2 style='color: #3498db;'>$verificationCode</h2>
            <p>Please enter this code on the verification page to activate your account.</p>
            <p>If you did not sign up, please ignore this email.</p>
            <p>Regards,<br>Verification Team</p>
        ";
        $mail->AltBody = "Dear $userName,\n\nYour verification code is: $verificationCode\n\nPlease enter this code on the verification page to activate your account.\n\nIf you did not sign up, please ignore this email.\n\nRegards,\nVerification Team";

        // Send the email
        $mail->send();
        echo 'Verification email has been sent.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
