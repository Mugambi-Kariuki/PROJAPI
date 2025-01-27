<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'PHPMailer/vendor/autoload.php';

// Example email address (replace this with the actual email address you want to use)
$email = 'recipient@example.com';

// Create an instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      
    $mail->isSMTP();                                         
    $mail->Host       = 'smtp.gmail.com';                   
    $mail->SMTPAuth   = true;                               
    $mail->Username   = 'yballer110@gmail.com';              
    $mail->Password   = 'eclgwdhuvxkxqtmi';                  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
    $mail->Port       = 465;                                 

    // Recipients
    $mail->setFrom('exempt@example.com', 'BBIT Exempt');
    
    // Validate email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail->addAddress($email);  
    } else {
        echo "Invalid email address.";
        exit;
    }

    // Generate reset token (example)
    $token = bin2hex(random_bytes(16));  // Generates a 32-character random token
    $resetLink = "https://localhost/htdocs/otp/php-password-reset/reset-password.php?token=$token";

    // Content
    $mail->isHTML(true);                                    // Set email format to HTML
    $mail->Subject = 'Reset Your Password';
    $mail->Body    = "
        <p>Hello,</p>
        <p>You requested to reset your password. Click the link below to reset it:</p>
        <p><a href='$resetLink'>$resetLink</a></p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Regards,<br>BBIT Exempt Team</p>
    ";

    // Optional plain text alternative
    $mail->AltBody = "Hello Caleb,\n\nYou requested to reset your password. Use the link below:\n$resetLink\n\nIf you did not request this, please ignore this email.\n\nRegards,\nBBIT Exempt Team";

    $mail->send();
    echo 'Reset password email has been sent.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}