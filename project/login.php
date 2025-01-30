<?php
session_start();
require 'includes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginResult = $user->login($email, $password);

    if (is_array($loginResult)) {
        $_SESSION['user'] = $loginResult;
        header("Location: home.php");
        exit();
    } elseif ($loginResult === "Not Verified") {
        echo "Please verify your email first.";
    } else {
        echo "Invalid email or password.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
