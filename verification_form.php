<?php
session_start();

// Check if the verification code exists in the session
if (!isset($_SESSION['verification_code'])) {
    die("No verification code found. Please start the verification process.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputCode = strtoupper(trim($_POST['verification_code'])); // Sanitize and normalize input
    $sessionCode = $_SESSION['verification_code'];

    if ($inputCode === $sessionCode) {
        // Successful verification
        echo "<h1>Verification Successful!</h1>";
        echo "<p>Your email and phone number have been verified.</p>";

        // Clear session data for security
        unset($_SESSION['verification_code']);
        unset($_SESSION['verification_email']);
    } else {
        // Failed verification
        $errorMessage = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            text-align: center;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Verification Code</h2>
        <p>Please enter the verification code sent to your email or phone.</p>
        <?php if (!empty($errorMessage)): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="verification_code" placeholder="Enter Code" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>