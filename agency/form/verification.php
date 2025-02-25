<?php
session_start();
require_once "../classes/database.php";
require "../vendor/autoload.php"; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'C:/Apache24/htdocs/projApi/error_log.txt');

// Debugging: Print session data
/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";*/

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['code'])) {
        $code = trim($_POST['code']);
    } else {
        $code = '';
    }
    $user_id = $_SESSION['user_id'];

    error_log("Received verification code: $code for user_id: $user_id");

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        error_log("Database connection failed.");
        die("Database connection failed.");
    }
    error_log("Database connection successful.");

    // Check if the code matches
    $stmt = $conn->prepare("SELECT * FROM user_verification WHERE user_id = ? AND verification_code = ?");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("is", $user_id, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        error_log("Verification code matched for user_id: $user_id");

        // Mark as verified
        $stmt = $conn->prepare("UPDATE user SET is_verified = 1 WHERE id = ?");
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            die("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        error_log("User marked as verified.");

        // Delete verification code
        $stmt = $conn->prepare("DELETE FROM user_verification WHERE user_id = ?");
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            die("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        error_log("Verification code deleted.");

        // Log redirection
        error_log("Redirecting to ../form/login.php");
        header("Location: ../form/login.php");
        exit();
    } else {
        error_log("Invalid verification code for user_id: $user_id");
        echo "Invalid verification code.";
    }
}

// Send verification code email if requested
if (isset($_POST['resend'])) {
    $email = $_SESSION['email'];
    $verification_code = rand(100000, 999999);

    // Store new verification code in the database
    $stmt = $conn->prepare("INSERT INTO user_verification (user_id, verification_code) 
                            VALUES (?, ?) ON DUPLICATE KEY UPDATE verification_code = ?");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("iis", $user_id, $verification_code, $verification_code);
    if (!$stmt->execute()) {
        error_log("Execute statement failed: " . $stmt->error);
        die("Execute statement failed: " . $stmt->error);
    }
    error_log("Verification code stored successfully.");

    // Send verification email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; 
        $mail->SMTPAuth = true;
        $mail->Username = "caleb.kariuki@strathmore.edu";
        $mail->Password = "vbfbayghqizgwxux";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->setFrom('caleb.kariuki@strathmore.edu', 'Footy Agency');
        $mail->addAddress($email);
        $mail->Subject = "Your New Verification Code";
        $mail->Body = "Your new verification code is: $verification_code";

        $mail->send();
        error_log("Sent verification code: $verification_code to email: $email");
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        die("Failed to send email.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .container form {
            margin-bottom: 10px;
        }
        .container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container button[type="submit"] {
            background-color: #28a745;
            color: #fff;
        }
        .container button[name="resend"] {
            background-color: #ffc107;
            color: #fff;
        }
        .container button:hover {
            opacity: 0.9;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="verificationForm" method="post">
            <input type="text" name="code" placeholder="Enter Verification Code" required>
            <button type="submit">Verify</button>
        </form>
        <form action="verification.php" method="POST">
            <button type="submit" name="resend">Resend Verification Code</button>
        </form>
        <div class="error-message" id="error-message" style="display: none;">
            ❌ Invalid verification code. Please try again.
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#verificationForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way
                $.ajax({
                    type: 'POST',
                    url: 'verification.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.includes('Invalid verification code')) {
                            $('#error-message').show();
                        } else {
                            window.location.href = '../form/login.php'; // Redirect to login page
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
