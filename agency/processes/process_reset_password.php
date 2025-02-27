<?php
require_once '../classes/database.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            exit;
        }

        // Check if email exists in the database
        $user = getUserByEmail($email);
        if (!$user) {
            echo "Email not found";
            exit;
        }

        // Generate a secure reset token
        $token = bin2hex(random_bytes(50));

        if (!savePasswordResetToken($email, $token)) {
            echo "Failed to generate reset token.";
            exit;
        }

        $resetLink = "http://yourdomain.com/agency/form/new_password.php?token=" . $token;

        if (sendResetEmail($email, $resetLink)) {
            echo "Password reset link has been sent to your email.";
        } else {
            echo "Failed to send email.";
        }
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    echo "Internal server error";
}
function getUserByEmail($email) {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            error_log("Connection failed: " . $conn->connect_error);
            return false;
        }

        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $user;
    } catch (Exception $e) {
        error_log("Exception in getUserByEmail: " . $e->getMessage());
        return false;
    }
}

// Function to save password reset token
function savePasswordResetToken($email, $token) {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            error_log("Connection failed: " . $conn->connect_error);
            return false;
        }

        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR)) 
                                ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return false;
        }

        $stmt->bind_param("ss", $email, $token);
        $result = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $result;
    } catch (Exception $e) {
        error_log("Exception in savePasswordResetToken: " . $e->getMessage());
        return false;
    }
}

//send email using PHPMailer
function sendResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);

    try {
       
        $mail->isSMTP();
        $mail->Host = 'smtp.yourdomain.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = "caleb.kariuki@strathmore.edu";
        $mail->Password = "vbfbayghqizgwxux";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; 

        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP Debug level $level; message: $str");
        };

        $mail->setFrom('caleb.kariuki@strathmore.edu', 'Footy Agency');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "<p>Click the link below to reset your password:</p>
                       <p><a href='$resetLink'>$resetLink</a></p>
                       <p>This link will expire in 1 hour.</p>";

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
