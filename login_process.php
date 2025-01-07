<?php
// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'dcConnection.php';
require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Start the session to store OTP
session_start();

// Database connection
$servername = "localhost:3308";
$username = "root"; 
$password = "caleb"; 
$dbname = "api_proj"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Input validation
    if (empty($email) || empty($username) || empty($password)) {
        // Save the error message in a session variable
        $_SESSION['error'] = "All fields are required.";
        header("Location: login.php"); // Redirect to the login page
        exit();
    }

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT username, email, password FROM user WHERE email = ? AND username = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($stored_username, $stored_email, $stored_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $stored_password)) {
            // Generate a random OTP
            $otp = random_int(100000, 999999);

            // Save OTP and email in session
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'yballer110@gmail.com'; 
                $mail->Password = 'viwgnqkfwjgthjsc'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('caleb.kariuki@strathmore.edu', 'YourAppName');
                $mail->addAddress($email);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Hello $stored_username,\n\nYour OTP code is: $otp\n\nPlease use this code to verify your login.";

                $mail->send();

                // Redirect to the OTP verification page
                header("Location: verification_form.php");
                exit();
            } catch (Exception $e) {
                // Save error message in a session variable
                $_SESSION['error'] = "Error sending OTP: " . $mail->ErrorInfo;
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No user found with that email and username.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
