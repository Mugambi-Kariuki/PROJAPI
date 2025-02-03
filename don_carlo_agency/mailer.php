<?php

require_once "../config.php";
require '../vendor/autoload.php';  // Corrected the path with proper syntax
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $this->test_input($_POST['name']);
    $email = $this->test_input($_POST['email']);
    $password = $this->test_input($_POST['password']);
    $role = $this->test_input($_POST['role']);

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0; // Disable debug output
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'caleb.kariuki@strathmore.edu';
        $mail->Password   = 'vbfb aygh qizg wxux';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('doncarloagency@example.com', 'Don Carlo Agency');
        $mail->addAddress($email, $name);

        // Generate verification code
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Account';
        $mail->Body    = "<p>Your verification code is: <b>{$verification_code}</b></p>";

        $mail->send();
        echo 'Message has been sent';

        // Encrypt password
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $query = "INSERT INTO users (name, email, password, role, verification_code, email_verified_at) 
                  VALUES (:name, :email, :password, :role, :verification_code, NULL)";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $encrypted_password,
            ':role' => $role,
            ':verification_code' => $verification_code
        ]);

        // Redirect for verification
        $email_sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        header("Location: userverification.php?email=" . urlencode($email_sanitized));
        exit();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
