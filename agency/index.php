<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Options</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        h2 {
            margin-top: 20px;
            font-style: italic;
            font-weight: bold;
        }
        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">GOATS Football Agency</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <!-- Register Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="registerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Register
                </a>
                <ul class="dropdown-menu" aria-labelledby="registerDropdown">
                    <li><a class="dropdown-item" href="../agency/form/register_agent.php">Register as Agent</a></li>
                    <li><a class="dropdown-item" href="../form/register_player.php">Register as Player</a></li>
                </ul>
            </li>

            <!-- Login Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Login
                </a>
                <ul class="dropdown-menu" aria-labelledby="loginDropdown">
                    <li><a class="dropdown-item" href="../agency/form/login.php">Player Login</a></li>
                    <li><a class="dropdown-item" href="../agency/form/agent_login.php">Agent Login</a></li>
                </ul>
            </li>
        </ul>

        <!-- Admin Login Icon (Aligned Right) -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="../agency/form/login_admin.php">
                    <i class="fas fa-wrench"></i> Admin
                </a>
            </li>
        </ul>
    </div>
</nav>

<h2>Welcome to the GOATS Football Agency</h2>
<p>The home of top agents in the transfer business</p>

</body>
</html>
