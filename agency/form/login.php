<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Login</title>
    <link rel="stylesheet" href="../css/style.css">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .navbar, .sidebar, .footer {
            background-color: #343a40;
            color: #ffffff;
        }
        .navbar a, .sidebar a, .footer a {
            color: #ffffff;
        }
        .login-image {
            position: relative;
            width: 100%;
            height: auto;
            margin-bottom: 20px;
            max-height: 150px; /* Adjust the max-height to make the image smaller */
        }
        .login-form {
            position: relative;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 300px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
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
                <li class="nav-item">
                    <a class="nav-link" href="../form/admin_login.php">Admin Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../form/agent_login.php">Agent Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex">
        <div class="sidebar bg-dark p-3">
            <h4>Menu</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">My Profile</a>
                </li>
            </ul>
        </div>
        <div class="content p-4">
            <h1>Player Login</h1>
            <div class="login-image">
                <img src="https://cdn.prod.website-files.com/5e305a6cb7083222527a89cc/66d854e464b418dc0a77b5f5_how_to_build_an_ai_agent.webp" alt="AI Agent">
            </div>
            <div class="login-form">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer bg-dark text-white mt-4 p-3">
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