<?php
require_once "DB.php";
require_once "User.php";
require 'PHPMailer/PHPMailerAutoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $user = new User();
    if ($user->register($name, $email, $password, $role)) {
        $verification_code = rand(100000, 999999);

        // Store verification code in session
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['verification_code'] = $verification_code;

        // Send verification email
        mail($email, "Verify Your Email", "Your verification code: " . $verification_code);
        
        header("Location: verify.php");
    } else {
        echo "Error registering user.";
    }
}
?>
