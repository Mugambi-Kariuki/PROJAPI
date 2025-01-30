<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'caleb.kariuki@strathmore.edu'; 
        $this->mail->Password = 'vbfb aygh qizg wxux'; 
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
    }

    public function sendVerificationCode($email, $verification_code) {
        try {
            $this->mail->setFrom('your-email@gmail.com', 'projectAPI');
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Your Verification Code';
            $this->mail->Body = "Your verification code is: $verification_code";
            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
