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
    <link rel="stylesheet" href="../css/style.css">
    <style>
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
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
        }
        label, input {
            margin: 5px 0;
        }
        input[type="submit"] {
            align-self: flex-end;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        #clubList div {
            cursor: pointer;
            padding: 5px;
            border: 1px solid #ddd;
        }
        #clubList div:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Welcome to your dashboard!</h1>
    <a href="#" id="openProfile">Profile</a>
    <button onclick="window.location.href='../dashboard/home.php'">Home</button>

    <!-- The Modal -->
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

        // Get the button that opens the modal
        var btn = document.getElementById("openProfile");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
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

        // Set club name from the list
        document.getElementById("clubList").addEventListener("click", function(e) {
            if (e.target && e.target.nodeName == "DIV") {
                document.getElementById("club").value = e.target.textContent;
                document.getElementById("clubList").innerHTML = "";
            }
        });

        // Validate club name and age before submitting the form
        document.getElementById("profileForm").addEventListener("submit", function(e) {
            var clubName = document.getElementById("club").value;
            var age = document.getElementById("age").value;

            // Validate age
            if (age < 18) {
                e.preventDefault();
                alert("Age cannot be less than 18.");
                return;
            }

            // Validate club name
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText.trim() !== "valid") {
                        e.preventDefault();
                        alert("The club name must be present in the clubs table.");
                    }
                }
            };
            xhr.open("GET", "../processes/validate_club.php?club=" + clubName, false);
            xhr.send();
        });
    </script>
</body>
</html>
