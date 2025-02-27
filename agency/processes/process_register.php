<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../classes/database.php';
require "../vendor/autoload.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User {
    private $conn;
    private $name;
    private $email;
    private $password;
    private $verification_code;

    public function __construct($conn, $name, $email, $password) {
        $this->conn = $conn;
        $this->name = trim($name);
        $this->email = trim($email);
        $this->password = $password;
        $this->verification_code = rand(100000, 999999);
    }

    public function validate() {
        if (empty($this->name) || empty($this->email) || empty($this->password)) {
            throw new Exception("All fields are required!");
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format!");
        }
    }

    public function register() {
        $this->validate();
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $this->conn->error);
        }
        $stmt->bind_param("sss", $this->name, $this->email, $hashedPassword);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $stmt = $this->conn->prepare("INSERT INTO user_verification (user_id, verification_code) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->conn->error);
            }
            $stmt->bind_param("is", $user_id, $this->verification_code);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            return $user_id;
        } else {
            throw new Exception("Error: " . $stmt->error);
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


$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $user = new User($conn, $_POST['name'], $_POST['email'], $_POST['password']);
        $user_id = $user->register();

        $mailer = new Mailer();
        $mailer->sendVerificationEmail($user->getEmail(), $user->getVerificationCode());

        session_start();
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
