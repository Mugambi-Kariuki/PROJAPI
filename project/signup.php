<?php
session_start();
require 'includes/database.php';
require 'includes/User.php';
require 'includes/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user = new User();
    $mailer = new Mailer();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $verification_code = $user->register($username, $email, $password);

    if ($verification_code) {
        $_SESSION['email'] = $email;
        $mailer->sendVerificationCode($email, $verification_code);
        header("Location: verification.php");
        exit();
    } else {
        echo "Signup failed. Try again.";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Sign Up</button>
</form>
