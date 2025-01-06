<?php
// dashboard.php

// Starting the session to check if the user is logged in
session_start();

// Check if user is logged in (you can replace this condition with your actual login check)
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// User details, you can fetch from the session if needed
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?php echo $username; ?> - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .banter-section {
            background-color: #e9ecef;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .banter {
            font-size: 18px;
            color: #555;
        }

        .banter h2 {
            color: #ff4500;
        }

        .logout-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, <?php echo $username; ?>!</h1>

    <!-- Random Cristiano Ronaldo Banter Section -->
    <div class="banter-section">
        <h2>Random Banter on Cristiano Ronaldo</h2>
        <div class="banter">
            <p>So, did you hear about Cristiano Ronaldo? They say he’s so fast, even his shadow can’t keep up with him!</p>
            <p>And you know that Cristiano Ronaldo is so good at football that when he kicks the ball, even the goalposts move to get out of his way!</p>
            <p>Honestly, Ronaldo’s been in top form, scoring goals like it’s a walk in the park. But rumor has it, when he scores, the ball high-fives him for doing all the hard work!</p>
            <p>If Ronaldo were a superhero, his superpower would definitely be 'Goal-scoring Vision.' They say he can see the goal even before the ball does!</p>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
