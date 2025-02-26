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
        <!-- Admin Login Icon (Aligned Right) -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="btn btn-dark" href="../agency/form/login_admin.php">
                    <i class="bi bi-tools"></i> Admin
                </a>
            </li>
        </ul>
    </div>
</nav>

<h2>Welcome to the GOATS Football Agency</h2>
<p>The home of top agents in the transfer business</p>
<p>At Thee Goats Footy Agency, we revolutionize football transfers by connecting players with top agents worldwide. Our platform is designed to make career moves seamless—whether you're a rising star looking for your first contract or an experienced player seeking new challenges. With just a few clicks, you can find and book professional agents who will negotiate the best deals for your next big transfer.</p>

<p>If it's your first time, you can 
    <a class="btn btn-warning" href="../agency/form/user_registration.php">REGISTER</a> 
    or just 
    <a class="btn btn-success" href="../agency/form/login.php">LOGIN</a>
    if you already have an account.
</p>

</body>
</html>
