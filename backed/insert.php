<?php
require 'dcConnection.php'; 
require 'vendor/autoload.php'; // Include Composer's autoloader for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Username = $_POST['Username'];
    $Email = $_POST['Email'];
    $Password = password_hash($_POST['Password'], PASSWORD_BCRYPT); // Hash the password
    $VerificationCode = rand(1000, 9999); // Generate a random 4-digit code

    try {
        // Insert user into the database with a verification status of 0 (unverified)
        $stmt = $conn->prepare("INSERT INTO clients (Username, Email, Password, VerificationCode, IsVerified) 
                                VALUES (:Username, :Email, :Password, :VerificationCode, 0)");
        $stmt->bindParam(':Username', $Username);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Password', $Password);
        $stmt->bindParam(':VerificationCode', $VerificationCode);

        $stmt->execute();

        // Send verification email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'caleb.kariuki@strathmore.edu'; // Your SMTP username
            $mail->Password = 'viwgnqkfwjgthjsc';    // Your SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 587;

            // Email content
            $mail->setFrom('two factor@example.com', 'CYCLA SYSTEMS');
            $mail->addAddress($Email);
            $mail->Subject = 'Your Verification Code';
            $mail->Body = "Hello $Username,\n\nYour verification code is: $VerificationCode.\n\nPlease enter this code to verify your account.";

            $mail->send();

            echo "<div class='container mt-5'><div class='alert alert-success text-center'>Registration successful! Please check your email for the verification code.</div></div>";
        } catch (Exception $e) {
            echo "<div class='container mt-5'><div class='alert alert-danger text-center'>Error: Unable to send verification email. {$mail->ErrorInfo}</div></div>";
        }
    } catch (PDOException $e) {
        echo "<div class='container mt-5'><div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div></div>";
    }
}
?>