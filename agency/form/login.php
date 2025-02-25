<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #000;
        }
        .navbar, .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #ffffff;
        }
        .content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 20px;
            background: url('https://img.freepik.com/free-photo/black-man-with-soccer-ball-pointing-with-finger_23-2148203602.jpg?uid=R184749194&ga=GA1.1.185349345.1737984667&semt=ais_hybrid') no-repeat center;
            background-size: cover; /* Ensures the image covers the available space */
            width: 100%;
            height: 160vh; /* Increase the height */
        }
        .login-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 350px;
            text-align: center;
            margin-left: 50px;
            transition: transform 0.3s ease; /* Add transition for hover effect */
        }
        .login-form:hover {
            transform: scale(1.05); /* Scale up the form on hover */
        }
        .footer {
            text-align: center;
            padding: 15px;
        }
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .login-form.dark-mode {
            background: rgba(0, 0, 0, 0.9);
            color: #ffffff;
        }
    </style>
</head>
<body class="dark-mode"> <!-- Enforce dark mode by default -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="toggleDarkMode">Toggle Dark Mode</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content">
        <div class="login-form dark-mode"> <!-- Apply dark mode to the form -->
            <h1>Player Login</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" action="../processes/process_login.php"> <!-- Add action attribute -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <a href="user_registration.php" class="btn btn-secondary btn-block">Register</a> <!-- Add registration button -->
                <a href="reset_password.php" class="btn btn-link btn-block">Forgot Password?</a> <!-- Add reset password button -->
            </form>
        </div>
    </div>

    <footer class="footer bg-dark text-white">
        <div class="container">
            <p>Contact: +254740905321</p>
            <p>Email: goatsagency@gmail.com</p>
            <button class="btn btn-primary" onclick="scrollToFooter()">Feedback</button>
            <button class="btn btn-secondary" onclick="scrollToTop()">↑</button>
        </div>
    </footer>

    <script>
        document.getElementById('toggleDarkMode').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            document.querySelector('.login-form').classList.toggle('dark-mode');
        });

        function scrollToFooter() {
            document.querySelector('.footer').scrollIntoView({ behavior: 'smooth' });
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
