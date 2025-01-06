<?php
// login_process.php

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
        echo "All fields are required.";
        exit;
    }

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT username, email, password FROM user WHERE email = ? AND username = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);  // Check if the statement preparation failed
    }

    // Bind the parameters (email and username)
    $stmt->bind_param("ss", $email, $username);
    
    // Execute the query
    $stmt->execute();
    $stmt->store_result();  // Store the result of the query
    $stmt->bind_result($stored_username, $stored_email, $stored_password);  // Bind result columns to variables

    if ($stmt->num_rows > 0) {
        // User found, fetch the result
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $stored_password)) {
            echo "Login successful. Welcome!";
            // Redirect to the dashboard or home page
            header("Location: verification.php"); // Replace 'dashboard.php' with the page you want to redirect to
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email and username.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
