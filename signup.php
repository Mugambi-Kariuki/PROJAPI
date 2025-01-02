<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #87CEEB; /* Sky Blue */
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            color: black;
        }

        input.form-control {
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        input.form-control:hover {
            background-color: #f0f0f0; /* Light grey on hover */
        }

        .btn-signup {
            background-color: green;
            color: white;
            border: none;
        }

        .btn-signup:hover {
            background-color: darkgreen;
        }

        .btn-login {
            background-color: grey;
            color: white;
            border: none;
        }

        .btn-login:hover {
            background-color: darkgrey;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="form-container col-md-6">
            <h1 class="text-center mb-4">Sign Up</h1>
            <form action="process_Signup.php" method="POST">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name:</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile Number:</label>
                    <input type="tel" id="mobile" name="mobile" class="form-control" pattern="[0-9]{10}" required>
                    <small class="form-text text-muted">Enter a 10-digit mobile number.</small>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-signup">Sign Up</button>
                </div>
            </form>
            <p class="text-center mt-3">
                Already have an account? 
                <a href="login.php" class="btn btn-login btn-sm">Login here</a>
            </p>
        </div>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
