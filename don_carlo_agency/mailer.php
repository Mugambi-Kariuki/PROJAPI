<?php

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->SMTPAuth = true;
$mail->Username = 'caleb.kariuki@strathmore.edu';
$mail->Password = 'vbfb aygh qizg wxux';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('your-email@example.com', 'Don Carlo Agency');
$mail->addAddress($user_email);
$mail->Subject = 'Verify Your Account';
$mail->Body = "Click here to verify your account: <a href='http://yourwebsite.com/verify.php?email=$user_email'>Verify</a>";

$mail->send();
?>