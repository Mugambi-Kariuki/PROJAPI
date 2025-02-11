<?php
require_once "Database.php";
require "vendor/autoload.php"; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($name, $password) {
        $stmt = $this->conn->prepare("SELECT id, email, password FROM users WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $email, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                // Generate a unique token
                $token = bin2hex(random_bytes(32));
                $stmt = $this->conn->prepare("UPDATE users SET token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $id);
                $stmt->execute();

                // Send email verification
                if ($this->sendVerificationEmail($email, $token)) {
                    return "Verification email sent! Check your inbox.";
                } else {
                    return "Failed to send verification email.";
                }
            } else {
                return "Incorrect password!";
            }
        } else {
            return "User not found!";
        }
    }

    private function sendVerificationEmail($email, $token) {
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = "smtp.example.com"; // Change to your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = "caleb.kariuki@strathmore.edu"; // Change to your email
            $mail->Password = "vbfbayghqizgwxux"; // Change to your email password
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            // Email settings
            $mail->setFrom("caleb.kariuki@strathmore,edu", "GOATs System");
            $mail->addAddress($email);
            $mail->Subject = "Email Verification";
            $mail->isHTML(true);

            $verificationLink = "http://localhost/verify_email.php?token=$token";
            $mail->Body = "<p>Click the link below to verify your email:</p>
                          <a href='$verificationLink'>$verificationLink</a>";

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

    public function verifyEmail($token) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id);
            $stmt->fetch();

            // Mark email as verified
            $stmt = $this->conn->prepare("UPDATE users SET token = NULL WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Redirect to home page
            header("Location: dashboard/home.php");
            exit();
        } else {
            return "Invalid verification token!";
        }
    }
}
?>
