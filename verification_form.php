<?php
session_start();
include 'conn.php';

// Check if the verification session exists
if (!isset($_SESSION['verification_data'])) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputCode = strtoupper(trim($_POST['verification_code'])); // Sanitize input
    $storedCode = $_SESSION['verification_data']['code'];
    $userId = $_SESSION['verification_data']['user_id'];

    // Query the database to verify the code
    $sql = "SELECT * FROM users WHERE id='$userId' AND verification_code='$storedCode'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data) {
        $codeExpiry = strtotime($data['code_expiry']);
        if ($codeExpiry >= time()) {
            // Successful verification
            $_SESSION['user_id'] = $data['id']; // Authenticate user
            unset($_SESSION['verification_data']); // Clear temporary session
            header("Location: dashboard.php");
            exit();
        } else {
            // Verification code expired
            echo "<script>
                alert('Verification code has expired. Please restart the process.');
                window.location.href = 'index.php';
            </script>";
        }
    } else {
        // Invalid verification code
        echo "<script>alert('Invalid verification code. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>
    <style>
        #container {
            border: 1px solid black;
            width: 400px;
            margin: 50px auto;
            height: 330px;
        }
        form {
            margin: 20px 50px;
        }
        p, h1 {
            margin-left: 50px;
        }
        input[type=text] {
            width: 290px;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            background-color: orange;
            border: 1px solid orange;
            width: 100px;
            padding: 9px;
            margin-left: 100px;
        }
        button:hover {
            cursor: pointer;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Email Verification</h1>
        <p>Enter the 6-digit verification code sent to your email address: 
            <?php echo $_SESSION['verification_data']['email']; ?>
        </p>
        <form method="post" action="">
            <label style="font-weight: bold; font-size: 18px;" for="verification_code">Verification Code:</label><br>
            <input type="text" name="verification_code" pattern="[A-Za-z0-9]{6}" placeholder="Enter Verification Code" required><br><br>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>

