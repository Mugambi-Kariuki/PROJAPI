<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include the database connection file
require_once 'dcConnection.php';
//Load Composer's autoloader
require 'vendor/autoload.php';
session_start();

// Login class extending the database connection
class Login extends dbconnection {
    public function login_user() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
    //Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'bonface.vulu@strathmore.edu';                     //SMTP username
    $mail->Password   = 'kvnmlqjuuapqyuwy';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

    //Recipients
    $mail->setFrom('APIapp@gmail.com', 'Mailer');
    $mail->addAddress($email,$name);     //Add a recipient
    //Content
    $mail->isHTML(true);
    $otp = random_int(100000, 999999);                                  //Set email format to HTML
    $mail->Subject = 'Verification Code';
    $mail->Body    = '<p>Verification code is: <b>'.$otp.'</b></p>';

    $mail->send();
    echo 'Message has been sent';
                // SQL query to fetch user details
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute();
    $user = $stmt->fetch();
    
                if ($user) {
                    // Verify password
                    if (password_verify($password, $user['password'])) {
                        echo '<script>alert("Login successful")</script>';
                            // Start user session and redirect
                            $_SESSION['email'] = $email;
                            header("Location: otf.php");
                            exit();
                        } else {
                            echo '<script>alert("Invalid password")</script>';
                        }
                    }else{
                        echo '<script>alert("No user found with this email")</script>';
                    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0; /* Remove default margin */
        }

        .header {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 40px; /* Increased padding for larger container */
            width: 500px; /* Enlarged the width of the container */
            margin-top: 30px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border: none;
        }

        .btn-primary:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        a {
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .form-check-label {
            font-size: 14px;
        }

        .nav-links {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>User Registration</h1>
        <div>
            <a href="register.php">Register</a> | 
            <a href="view_users.php">View Users</a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="container">
        <div class="login-card">
            <h3 class="text-center mb-4">User Login</h3>
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePasswordVisibility()">
                        <label class="form-check-label" for="showPassword">Show password</label>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                </div>
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="usersignup.php">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("exampleInputPassword1");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    }
}