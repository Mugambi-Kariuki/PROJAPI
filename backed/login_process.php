<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/vendor/autoload.php'; // Include PHPMailer
require 'dcConnection.php';   // Include database connection

// Function to generate OTP (6-digit random number)
function generateOTP($length = 6) {
    return str_pad(random_int(0, 10**$length - 1), $length, '0', STR_PAD_LEFT);
}

// Function to send OTP via email using PHPMailer
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'caleb.kariuki@strathmore.edu';  
        $mail->Password = 'qhjz iogp gikz hbvc';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('caleb.kariuki@strathmore.edu', 'Your App');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code for Verification';
        $mail->Body    = "Your OTP code is: <strong>$otp</strong><br>Please use this code to verify your login.";

        // Send email
        $mail->send();
        echo 'OTP has been sent to your email.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Process login request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a database connection
    $db = new DatabaseConnection();
    $pdo = $db->getConnection();

    // Check if the user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify password (assumes passwords are hashed)
        if (password_verify($password, $user['password'])) {
            // Generate OTP
            $otp = generateOTP();

            // Store OTP in the database for later verification
            $otpStmt = $pdo->prepare("UPDATE users SET otp = :otp WHERE email = :email");
            $otpStmt->execute(['otp' => $otp, 'email' => $email]);

            // Send OTP to user's email
            sendOTPEmail($email, $otp);

            // Redirect to OTP verification page
            header('Location: verification_form.php');
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>
