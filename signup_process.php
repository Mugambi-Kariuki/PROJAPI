<?php
// signup_process.php

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

// Get form data
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$mobile = $_POST['mobile'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and bind the SQL query
$stmt = $conn->prepare("INSERT INTO user (firstname, lastname, mobile, username, email, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $firstname, $lastname, $mobile, $username, $email, $hashed_password);

// Execute the query and check for success
if ($stmt->execute()) {
    echo "Account created successfully!";
    //go to login
    header("Location: login.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
