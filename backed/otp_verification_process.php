<?php
// login_process.php
require 'login_process.php';
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
    //$username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Input validation
    if (empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT  email, password FROM user WHERE email = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);  // Check if the statement preparation failed
    }

    //parameters (email and username)
    $stmt->bind_param("ss", $email, $username);
    
    
    $stmt->execute();
    $stmt->store_result();  
    $stmt->bind_result($stored_username, $stored_email, $stored_password);  

    if ($stmt->num_rows > 0) {
        // User found, fetch the result
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $stored_password)) {
            echo "Login successful. Welcome!";
            // Redirect to the verification page
            header("Location: verification_form.php"); 
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
