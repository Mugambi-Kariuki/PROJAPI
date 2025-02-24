<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Options</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .gif-container {
            width: 100%;
            max-height: 300px;
            overflow: hidden;
        }
        .gif-container img {
            width: 100%;
            object-fit: cover;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .login-options {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .login-box {
            width: 150px;
            padding: 20px;
            background: #e9e9e9;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-box:hover {
            background: #ccc;
        }
        .login-box i {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .login-box p {
            font-size: 18px;
            font-weight: bold;
        }
        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>

<!-- GIF Section -->
<div class="gif-container">
    <img src="https://media.tenor.com/vyglLbfbNWsAAAAC/argentina-world-cup.gif" alt="Argentina World Cup Trophy Lift">
</div>

<div class="container">
    <h2>Select Login Option</h2>
    <div class="login-options">
        <!-- Admin Login -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="registerDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Register
            </button>
            <div class="dropdown-menu" aria-labelledby="registerDropdown">
                <a class="dropdown-item" href="../agency/form/register_agent.php">Register as Agent</a>
                <a class="dropdown-item" href="../form/register_player.php">Register as Player</a>
            </div>
        </div>

        <!-- Player Login -->
        <a href="../agency/form/login.php">
            <div class="login-box">
                <i class="fas fa-futbol"></i>
                <p>Player</p>
            </div>
        </a>

        <!-- Agent Login -->
        <a href="../agency/form/agent_login.php">
            <div class="login-box">
                <i class="fas fa-user-tie"></i>
                <p>Agent</p>
            </div>
        </a>
    </div>
</div>

</body>
</html>
