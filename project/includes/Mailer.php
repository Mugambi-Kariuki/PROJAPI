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


//new
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure you have installed PHPMailer via Composer

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        try {
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'caleb.kariuki@strathmore.edu';
            $this->mail->Password = 'vbfb aygh qizg wxux'
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;
            $this->mail->setFrom('your-email@gmail.com', 'Website Admin');
        } catch (Exception $e) {
            echo "Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function sendVerificationCode($email, $message) {
        try {
            $this->mail->addAddress($email);
            $this->mail->Subject = 'Verification Code';
            $this->mail->Body = $message;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>