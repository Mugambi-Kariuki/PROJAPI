<?php
session_start();
require_once "../classes/database.php"; 
require "../vendor/autoload.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User {
    private $conn;
    private $email;
    private $verification_code;

    public function __construct($conn, $email) {
        $this->conn = $conn;
        $this->email = trim($email);
        $this->verification_code = rand(100000, 999999);
    }

    public function validate() {
        if (empty($this->email)) {
            throw new Exception("Email is required!");
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format!");
        }
    }

    public function resendVerification() {
        $this->validate();

        $stmt = $this->conn->prepare("SELECT id FROM user WHERE email = ?");
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $this->conn->error);
        }
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            $stmt = $this->conn->prepare("UPDATE user_verification SET verification_code = ? WHERE user_id = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->conn->error);
            }
            $stmt->bind_param("si", $this->verification_code, $user_id);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            return $user_id;
        } else {
            throw new Exception("No user found with this email!");
        }
    }

    public function getVerificationCode() {
        return $this->verification_code;
    }

    public function getEmail() {
        return $this->email;
    }
}

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = "smtp.gmail.com";
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "caleb.kariuki@strathmore.edu";
        $this->mail->Password = "vbfbayghqizgwxux";
        $this->mail->SMTPSecure = "tls";
        $this->mail->Port = 587;
        $this->mail->setFrom('caleb.kariuki@strathmore.edu', 'Footy Agency');
    }

    public function sendVerificationEmail($email, $verification_code) {
        try {
            $this->mail->addAddress($email);
            $this->mail->Subject = "Your Verification Code";
            $this->mail->Body = "Your verification code is: $verification_code";
            $this->mail->send();
        } catch (Exception $e) {
            throw new Exception("Failed to send email: " . $this->mail->ErrorInfo);
        }
    }
}

// database connection instance
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    error_log("Database connection failed.");
    die("Database connection failed.");
}
error_log("Database connection successful.");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $user = new User($conn, $_POST['email']);
        $user_id = $user->resendVerification();

        $mailer = new Mailer();
        $mailer->sendVerificationEmail($user->getEmail(), $user->getVerificationCode());

        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $user->getEmail();
        header("Location: ../form/verification.php");
        exit();
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

$conn->close();
?>
