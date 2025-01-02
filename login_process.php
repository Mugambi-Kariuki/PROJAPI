<?php
// Database configuration
$servername = "localhost:3308";
$username = "root";
$password = "caleb"; // Replace with your database password
$dbname = "api_proj";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
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
    $stmt = $conn->prepare("SELECT id, password FROM Users WHERE email = ? AND username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $stored_password);

    if ($stmt->num_rows > 0) {
        // User found, verify password
        $stmt->fetch();
        if (password_verify($password, $stored_password)) {
            echo "Login successful. Welcome!";
            // You can set a session or redirect to another page
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email and username.";
    }

    $stmt->close();
}

$conn->close();
?>
