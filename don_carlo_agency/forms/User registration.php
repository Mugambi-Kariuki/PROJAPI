<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Don Carlo Agency</title>
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
            overflow: hidden;
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

        /* Form container */
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

        /* Styled submit button */
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

        /* Password container */
        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
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

        /* Slideshow Container */
        .slideshow-container {
            position: absolute;
            top: 50%;
            left: 5%;
            width: 500px;
            height: 300px;
            transform: translateY(-50%);
            z-index: 2;
        }

        .slide {
            display: none;
        }

        .slide img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

    </style>
</head>
<body>

    <!-- Image Slideshow -->
    <div class="slideshow-container">
        <div class="slide"><img src="https://media.gettyimages.com/photos/lionel-messi-in-barcelona-kit-picture-id1207890913" alt="Messi Playing"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/lionel-messi-in-suit-picture-id1187747744" alt="Messi Suit"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/harry-kane-playing-kit-picture-id1170300939" alt="Kane Playing"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/harry-kane-in-suit-picture-id1207853185" alt="Kane Suit"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/thierry-henry-playing-kit-picture-id1140348832" alt="Henry Playing"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/thierry-henry-in-suit-picture-id1189339024" alt="Henry Suit"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/andres-iniesta-playing-kit-picture-id1038376032" alt="Iniesta Playing"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/andres-iniesta-in-suit-picture-id1207869097" alt="Iniesta Suit"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/kaka-playing-kit-picture-id1167499926" alt="Kaka Playing"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/kaka-in-suit-picture-id1188663398" alt="Kaka Suit"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/sergio-aguero-playing-kit-picture-id1131430589" alt="Aguero Playing"></div>
        <div class="slide"><img src="https://media.gettyimages.com/photos/sergio-aguero-in-suit-picture-id1207863429" alt="Aguero Suit"></div>
    </div>

    <!-- Registration Form -->
    <div class="form-container">
        <h2>Register</h2>
        <form action="../back/register.php" method="POST">
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

            <select name="role" required>
                <option value="player">Player</option>
                <option value="agent">Agent</option>
            </select>

            <input type="number" name="age" placeholder="Age" required>
            <input type="text" name="nationality" placeholder="Nationality" required>
            <input type="text" name="current_club" placeholder="Current Club" required>
            <input type="number" name="salary" placeholder="Salary" required>

            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="user_login.php">Login here</a></p>
    </div>

    <script>
        let slideIndex = 0;
        function showSlides() {
            let slides = document.querySelectorAll(".slide");
            slides.forEach(slide => slide.style.display = "none");
            slideIndex = (slideIndex + 1) % slides.length;
            slides[slideIndex].style.display = "block";
            setTimeout(showSlides, 3000);
        }
        showSlides();
    </script>

</body>
</html>
