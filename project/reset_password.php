<?php
require 'includes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_GET['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $user->conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $new_password, $token);
    $stmt->execute();

    echo "Password updated! You can now <a href='login.php'>login</a>.";
}
?>

<form method="POST">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>
