<?php
require_once "User.php";
require 'PHPMailer/PHPMailerAutoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $loggedUser = $user->login($email, $password);

    if ($loggedUser) {
        if ($loggedUser['verified'] == 0) {
            $verification_code = rand(100000, 999999);
            mail($email, "Your Verification Code", "Your code is: " . $verification_code);

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['verification_code'] = $verification_code;

            header("Location: verify.php");
        } else {
            $_SESSION['user'] = $loggedUser;
            header("Location: dashboard.php");
        }
    } else {
        echo "Invalid login!";
    }
}
?>
