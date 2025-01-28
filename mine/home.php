<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 15px;
            background-color: #f4f4f4;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        header h1 {
            margin: 5px;
            font-size: 18px;
        }

        .icon {
            font-size: 18px;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 8px 10px;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }

        .images {
            width: 50%;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 8px;
            position: relative;
        }

        .images img {
            width: 70%;
            height: auto;
            border-radius: 8px;
            display: none; /* Hide all images by default */
            transition: opacity 2s ease-in-out;
        }

        .images img.active {
            display: block; /* Show the active image */
            opacity: 1;
        }

        .images img.fade-out {
            opacity: 0; /* Add fade-out effect */
        }

        .emoji {
            font-size: 100px;
        }

        .info {
            margin-top: 20px;
            text-align: center;
        }

        footer {
            margin-top: 50px;
            padding: 10px;
            text-align: center;
            background-color: #f4f4f4;
            font-size: 12px;
        }

        footer p {
            margin: 3px 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Vista</h1>
    <div>
        <span class="icon">&#127969;</span>
        <div class="dropdown">
            <span class="icon">&#x22EE;</span>
            <div class="dropdown-content">
                <a href="#footer" onclick="scrollToFooter()">About Us</a>
                <a href="#footer" onclick="scrollToFooter()">Contact Info</a>
                <a href="bookings.php">Bookings</a>
            </div>
        </div>
    </div>
</header>

<div class="content">
    <div class="emoji">&#128564;</div>
    <div class="info">
        <p id="time"></p>
        <p id="location"></p>
    </div>

    <div class="images" id="slideshow">
        <img src="images/bed.jpg" alt="Comfortable bed" class="active">
        <img src="https://img.freepik.com/premium-photo/breakfast-with-egg-toast-coffee-3d_951116-209.jpg" alt="Delicious breakfast">
        <img src="https://cdn.shopify.com/s/files/1/0336/3763/0092/files/blissbulb_blue_square_small_45bd9817-2beb-4bdf-98e7-e0e9886e670a_1024x1024.jpg?v=1608058984" alt="Light setup">
        <img src="https://img.freepik.com/premium-photo/woman-hands-taking-shower_153437-2688.jpg?w=900" alt="Hot shower">
        <img src="https://img.freepik.com/free-photo/light-room-furniture-luxury-retro_1203-4673.jpg?t=st=1737984735~exp=1737988335~hmac=def4a13ac832c76aad6bbfed30fdfc02d611facc0b0603466e1867852dafa1c4&w=900" alt="Basic study desk">
    </div>
</div>

<footer id="footer">
    <p>&copy; 2025</p>
    <p>Contact Number: +25440905321</p>
    <p>Email: yballer110@gmail.com</p>
</footer>

<script>
    // Function to scroll to the footer
    function scrollToFooter() {
        document.getElementById('footer').scrollIntoView({ behavior: 'smooth' });
    }

    // Function to update the time
    function updateTime() {
        const now = new Date();
        const time = now.toLocaleTimeString();
        document.getElementById('time').innerText = `Current Time: ${time}`;
    }

    // Function to get user's location
    function updateLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const { latitude, longitude } = position.coords;
                document.getElementById('location').innerText = `Your Location: Latitude ${latitude.toFixed(2)}, Longitude ${longitude.toFixed(2)}`;
            }, () => {
                document.getElementById('location').innerText = "Unable to fetch your location.";
            });
        } else {
            document.getElementById('location').innerText = "Geolocation is not supported by your browser.";
        }
    }

    // Slideshow logic
    let currentIndex = 0;
    const images = document.querySelectorAll('#slideshow img');

    function showNextImage() {
        images[currentIndex].classList.remove('active');
        images[currentIndex].classList.add('fade-out');
        currentIndex = (currentIndex + 1) % images.length;
        images[currentIndex].classList.remove('fade-out');
        images[currentIndex].classList.add('active');
    }

    setInterval(showNextImage, 2000); // Change image every 3 seconds

    // Update the time and location when the page loads
    updateTime();
    updateLocation();

    // Update the time every second
    setInterval(updateTime, 1000);
</script>

</body>
</html>
