<?php
require_once "classes/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $password = $_POST['password'];

    $user = new User();
    $message = $user->login($name, $password);

    echo $message;
}
?>
