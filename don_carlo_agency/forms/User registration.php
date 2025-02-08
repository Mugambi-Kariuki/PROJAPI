<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Don Carlo Agency</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Football-themed Background */
        body {
            background: url('https://source.unsplash.com/1600x900/?football-stadium') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Dark Overlay for readability */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .form-container {
            position: relative;
            z-index: 2;
            max-width: 500px;
            padding: 25px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .form-container h2 {
            color: #333;
        }

        .form-container input, 
        .form-container select, 
        .form-container button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }

        .form-container button:hover {
            background-color: #2e7d32;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 35px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }

        /* Login Link */
        .form-container p {
            margin-top: 10px;
            font-size: 14px;
        }

        .form-container p a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>User Registration</h2>
        <form action="../back/user_registration.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>

            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

            <input type="number" name="age" placeholder="Age" required>
            <input type="text" name="nationality" placeholder="Nationality" required>
            <input type="text" name="current_club" placeholder="Current Club" required>
            <input type="number" name="salary" placeholder="Salary" required>

            <button type="submit" name="register">Register</button>
        </form>

        <p>Already have an account? <a href="user_login.php">Login here</a></p>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var confirmPasswordField = document.getElementById("confirm_password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmPasswordField.type = "text";
            } else {
                passwordField.type = "password";
                confirmPasswordField.type = "password";
            }
        }
    </script>
</body>
</html>
