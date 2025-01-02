<?php
session_start();

// Include database connection
require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in both fields.";
        header("Location: login.php");
        exit();
    }

    try {
        // Prepare SQL query to fetch user details
        $query = "SELECT id, username, password_hash FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password_hash'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                // Invalid password
                $_SESSION['error'] = "Invalid username or password.";
            }
        } else {
            // Invalid username
            $_SESSION['error'] = "Invalid username or password.";
        }

        $stmt->close();
    } catch (Exception $e) {
        // Handle database errors
        $_SESSION['error'] = "An error occurred. Please try again.";
        error_log($e->getMessage()); // Log the error for debugging
    }

    // Redirect back to the login page on failure
    header("Location: login.php");
    exit();
} else {
    // Redirect to login page if accessed directly
    header("Location: login.php");
    exit();
}
