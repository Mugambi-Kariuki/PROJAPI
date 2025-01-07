<?php
session_start();
include 'dcConnection.php'; // Database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email'] = $email;

    // Query to retrieve user details
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data && password_verify($password, $data['password'])) {
        // Generate a secure verification code
        $otp = rand(100000, 999999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minutes"));
        $subject = "Your OTP for Login";
        $message = "
            <h1>Login Verification</h1>
            <p>Here is your One-Time Password (OTP):</p>
            <h2 style='color: blue;'>$otp</h2>
            <p>This code is valid for 3 minutes.</p>
            <p>If you didn't request this, please ignore this email.</p>
        ";

        // Send the OTP via email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yballer110@gmail.com'; // Your Gmail address
            $mail->Password = 'viwgnqkfwjgthjsc'; // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('caleb.kariuki@strathmore.edu', 'Verification System');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();

            // Update database with OTP and expiry
            $updateSql = "UPDATE users SET otp = ?, otp_expiry = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('ssi', $otp, $otp_expiry, $data['id']);
            $updateStmt->execute();

            // Save temporary user data and redirect
            $_SESSION['temp_user'] = ['id' => $data['id'], 'otp' => $otp];
            header("Location: otp_verification.php");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>
                alert('Invalid Email or Password. Please try again.');
                window.location.href = 'index.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        #container {
            margin: 40px auto;
            width: 440px;
            padding: 20px;
            border: 1px solid black;
        }
        input[type=text], input[type=password] {
            width: 300px;
            height: 20px;
            padding: 10px;
        }
        label {
            font-size: 20px;
            font-weight: bold;
        }
        form {
            margin-left: 50px;
        }
        a {
            text-decoration: none;
            font-weight: bold;
            font-size: 21px;
            color: blue;
        }
        a:hover {
            cursor: pointer;
            color: purple;
        }
        input[type=submit] {
            width: 70px;
            background-color: blue;
            border: 1px solid blue;
            color: white;
            font-weight: bold;
            padding: 7px;
            margin-left: 130px;
        }
        input[type=submit]:hover {
            background-color: purple;
            cursor: pointer;
            border: 1px solid purple;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="">
            <label for="email">Email</label><br>
            <input type="text" name="email" placeholder="Enter Your Email" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter Your Password" required><br><br>
            <input type="submit" name="login" value="Login"><br><br>
            <label>Don't have an account? </label><a href="registration.php">Sign Up</a>
        </form>
    </div>
</body>
</html>
