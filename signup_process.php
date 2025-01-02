<?php
// Database configuration
$servername = "localhost:3308";
$username = "root";
$password = "caleb"; // Replace with your DB password
$dbname = "api_proj";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $mobile = trim($_POST['mobile']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate OTP
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // Input validation
    if (empty($firstname) || empty($lastname) || empty($mobile) || empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Insert user details into the database
    $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, mobile, username, email, password, verification_code, verification_expiry) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstname, $lastname, $mobile, $username, $email, $hashedPassword, $otp, $expiry);

    if ($stmt->execute()) {
        // Send OTP via email (or SMS integration for mobile)
        $to = $email;
        $subject = "Verify Your Account";
        $message = "Your verification code is: $otp. It will expire in 10 minutes.";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($to, $subject, $message, $headers)) {
            header("Location: verify_account.php?email=$email");
            exit;
        } else {
            echo "Error sending verification code.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
