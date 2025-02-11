<?php
session_start();
require_once "../classes/Database.php"; 
require_once "../classes/User.php";
require "../vendor/autoload.php"; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', '/c:/Apache24/htdocs/projApi/error_log.txt');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $user = new User();
    $result = $user->login($email, $password);

    if ($result['status'] === 'success') {
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['email'] = $result['email'];

        if ($result['is_verified'] == 0) {
            // Generate verification code
            $verification_code = rand(100000, 999999);
            
            // Store verification code in the database
            $db = new Database();
            $conn = $db->getConnection();

            if ($conn) {
                $stmt = $conn->prepare("INSERT INTO user_verification (user_id, verification_code) VALUES (?, ?) ON DUPLICATE KEY UPDATE verification_code = ?");
                if ($stmt) {
                    $stmt->execute([$result['user_id'], $verification_code, $verification_code]);
                } else {
                    error_log("Failed to prepare statement.");
                    echo "Failed to prepare statement.";
                    exit();
                }
            } else {
                error_log("Failed to connect to the database.");
                echo "Failed to connect to the database.";
                exit();
            }

            // Send verification email
            $mail = new PHPMailer(true);
            try {
                $mail->setFrom('caleb.kariuki@strathmore.edu', 'Footy Agency');
                $mail->addAddress($result['email']);
                $mail->Subject = "Verify Your Account";
                $mail->Body = "Your verification code is: $verification_code";
                $mail->send();
            } catch (Exception $e) {
                error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                exit();
            }

            // Redirect to verification page
            header("Location: ../agency/form/verify.php");
            exit();
        } else {
            // Redirect to dashboard if verified
            header("Location: ../agency/form/dashboard.php");
            exit();
        }
    } else {
        echo $result['message']; // Display error message
    }
}
?>
