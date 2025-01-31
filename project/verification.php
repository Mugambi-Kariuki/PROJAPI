<?php
session_start();
require 'includes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $email = $_SESSION['email'];
    $code = $_POST['code'];

    if ($user->verifyUser($email, $code)) {
        header("Location: login.php");
        exit();
    } else {
        echo "Invalid verification code.";
    }
}
?>

<form method="POST">
    <input type="text" name="code" placeholder="Enter Verification Code" required>
    <button type="submit">Verify</button>
</form>
