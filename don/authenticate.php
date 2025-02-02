<?php
require_once "User.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $loggedUser = $user->login($email, $password);

    if ($loggedUser) {
        if ($loggedUser['verified'] == 0) {
            $_SESSION['email'] = $email;
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
