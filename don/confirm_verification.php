<?php
require_once "User.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['verification_code'];

    if ($entered_code == $_SESSION['verification_code']) {
        $user = new User();
        $user->verifyEmail($_SESSION['email']);
        echo "Email verified! <a href='login.php'>Login</a>";
    } else {
        echo "Invalid code!";
    }
}
?>
