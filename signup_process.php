<?php
// Database configuration
$servername = "localhost:3308";
$username = "root";
$password = "caleb"; 
$dbname = "api_proj";

// Create connection
//$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Input validation
    if (empty($firstname) || empty($lastname) || empty($mobile) || empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        echo "Invalid mobile number. Please enter a 10-digit number.";
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO Users (username, firstname, lastname, email,  mobile, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $mobile, $hashedPassword);

    if ($stmt->execute()) {
        echo "Sign-up successful. <a href='login.php'>Login here</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
