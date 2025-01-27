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
            padding: 5px 15px;
            background-color: #f4f4f4;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 14px;
        }

        header h1 {
            margin: 0;
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 90%;
            margin-top: 30px;
        }

        .images img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
                <a href="bookings.html">Bookings</a>
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

    <div class="images">
        <img src="images/lodging-bed.jpg" alt="4-star lodging bed">
        <img src="https://img.freepik.com/premium-photo/breakfast-with-egg-toast-coffee-3d_951116-209.jpg" alt = "Delicious breakfast">
        <img src="images/proper-lights.jpg" alt="Proper lighting setup">
        <img src="images/hot-shower.jpg" alt="Hot shower">
        <img src="images/study-desk.jpg" alt="Basic study desk">
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

    // Update the time and location when the page loads
    updateTime();
    updateLocation();

    // Update the time every second
    setInterval(updateTime, 1000);
</script>

</body>
</html>