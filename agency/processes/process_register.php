<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Correct the path to the Database class file
require_once '../classes/database.php'; //database connection
require "../vendor/autoload.php"; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// database connection instance
$db = new Database();
$conn = $db->getConnection();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $verification_code = rand(100000, 999999); // Generate a random verification code

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        die("All fields are required!");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Insert verification code
            $stmt = $conn->prepare("INSERT INTO user_verification (user_id, verification_code) VALUES (?, ?)");
            if (!$stmt) {
                error_log("Prepare statement failed: " . $conn->error);
                die("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("is", $user_id, $verification_code);
            if (!$stmt->execute()) {
                error_log("Execute statement failed: " . $stmt->error);
                die("Execute statement failed: " . $stmt->error);
            }
            error_log("Verification code inserted successfully.");

            // Log successful insertion
            error_log("Inserted verification code $verification_code for user_id: $user_id");

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
                $mail->Subject = "Your Verification Code";
                $mail->Body = "Your verification code is: $verification_code";

                $mail->send();
                error_log("Sent verification code: $verification_code to email: $email");
            } catch (Exception $e) {
                error_log("Email Error: " . $mail->ErrorInfo);
                die("Failed to send email.");
            }

            // Redirect to verification page
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email; // Store email in session for sending verification code
            header("Location: ../form/verification.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
