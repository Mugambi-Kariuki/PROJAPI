<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST['otp']);
    if ($user_otp == $_SESSION['otp']) {
        echo "OTP verified successfully!";
        // Redirect to dashboard or home page
        header("Location: otp_verification_process.php");
        exit();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>
<form method="post">
    <label for="otp">Enter OTP:</label>
    <input type="text" name="otp" required>
    <button type="submit">Verify OTP</button>
</form>
