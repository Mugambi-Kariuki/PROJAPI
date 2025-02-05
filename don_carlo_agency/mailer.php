/*<?php
require_once "../config.php";
require '../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Register {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function registerUser($name, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
        // 6-digit verification code
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    
        $query = "INSERT INTO user (name, email, password, role, verification_code, email_verified_at) 
                  VALUES (:name, :email, :password, :role, :verification_code, NULL)";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":verification_code", $verification_code);
    
        if ($stmt->execute()) {
            return $this->sendVerificationEmail($email, $verification_code, $name);
        }
        return false;
    }

    private function sendVerificationEmail($email, $verification_code, $name) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'caleb.kariuki@strathmore.edu'; 
            $mail->Password = 'vbfbayghqizgwxux'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
    
            $mail->setFrom('caleb.kariuki@strathmore.edu', 'Don Carlo Agency');
            $mail->addAddress($email, $name);
    
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification Code';
            $mail->Body    = "<p>Your verification code is: <b>{$verification_code}</b></p>";
    
            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}

// Process Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $register = new Register();
    if ($register->registerUser($name, $email, $password, $role)) {
        $email_sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        header("Location: ../forms/verification.php?email=" . urlencode($email_sanitized));
        exit();
    } else {
        echo "Error registering user.";
    }
}
?>



