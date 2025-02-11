<?php
require_once "classes/User.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $user = new User();
    echo $user->verifyEmail($token);
} else {
    echo "No verification token provided!";
}
?>
