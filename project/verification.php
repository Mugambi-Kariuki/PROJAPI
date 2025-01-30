<?php
session_start();
require 'includes/User.php';

if (!isset($_SESSION['email'])) {
    header("Location: signup.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $email = $_SESSION['email'];
    $code = $_POST['code'];

    if ($user->verifyUser($email, $code)) {
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
        echo "Invalid verification code.";
    }
}
?>

<form method="POST">
    <input type="text" name="code" placeholder="Enter verification code" required>
    <button type="submit">Verify</button>
</form>
