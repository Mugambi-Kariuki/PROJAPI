<?php
session_start();
require 'includes/User.php';
require 'includes/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    $user = new User();
    $mailer = new Mailer();

    $stmt = $user->conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    $reset_link = "http://localhost/reset_password.php?token=$token";
    $mailer->sendVerificationCode($email, "Click here to reset your password: $reset_link");

    echo "Check your email for reset instructions.";
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Reset Password</button>
</form>
