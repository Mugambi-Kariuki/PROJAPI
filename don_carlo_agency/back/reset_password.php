<?php
session_start();
require '../config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resetData) {
        die("Invalid or expired token.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $resetData['email'];

        // Update user password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$new_password, $email]);

        // Remove used token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$email]);

        $_SESSION['success'] = "Password updated successfully!";
        header("Location: ../forms/user login.php");
        exit();
    }
} else {
    die("No token provided.");
}
?>