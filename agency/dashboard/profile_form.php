<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../form/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            text-align: center;
        }
        .dashboard-header {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 30px;
        }
        .lead {
            font-size: 1.2rem;
            font-weight: 500;
        }
        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
            border: none;
        }
        .btn-info:hover {
            background-color: #138496;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
                    <a class="nav-link" href="../form/logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="dashboard-header">Welcome to Your Dashboard!</h1>
        <p>Your personal hub for managing your football career. Update your profile, find professional agents, and explore opportunities.</p>

        <a href="#" id="openProfile" class="btn btn-info">Create my profile</a>

        <h2 class="section-title mt-4">Find an Agent</h2>
        <p class="lead">👉 Find our agents here to help you with contract negotiations, club transfers, and career advice.</p>
        <button class="btn btn-primary" onclick="window.location.href='../dashboard/home.php'">Agents</button>
    </div>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="profileForm" action="../processes/save_profile.php" method="post">
                <h2>Kindly fill in your details here</h2>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required><br><br>
                <label for="club">Club:</label>
                <input type="text" id="club" name="club" required onkeyup="searchClub(this.value)"><br><br>
                <div id="clubList"></div>
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" required><br><br>
                <label for="nationality">Nationality:</label>
                <input type="text" id="nationality" name="nationality" required><br><br>
                <label for="salary">Salary:</label>
                <input type="number" step="0.01" id="salary" name="salary" required><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("profileModal");
        var btn = document.getElementById("openProfile");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // AJAX search for clubs
        function searchClub(query) {
            if (query.length == 0) {
                document.getElementById("clubList").innerHTML = "";
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("clubList").innerHTML = this.responseText;
                }
            };
            xhr.open("GET", "../processes/search_club.php?q=" + query, true);
            xhr.send();
        }

        document.getElementById("clubList").addEventListener("click", function(e) {
            if (e.target && e.target.nodeName == "DIV") {
                document.getElementById("club").value = e.target.textContent;
                document.getElementById("clubList").innerHTML = "";
            }
        });

        document.getElementById("profileForm").addEventListener("submit", function(e) {
            var age = document.getElementById("age").value;
            if (age < 18) {
                e.preventDefault();
                alert("Age cannot be less than 18.");
                return;
            }
        });

        document.getElementById('toggleDarkMode').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>
</html>
