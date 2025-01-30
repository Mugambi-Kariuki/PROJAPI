<?php
require_once 'database.php';

//20 rooms as available
$rooms = array_fill(1, 20, true); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $room = (int)$_POST['room'];
    $preference = htmlspecialchars($_POST['preference']);
    $checkin = htmlspecialchars($_POST['checkin']);

    if ($rooms[$room]) {
        $rooms[$room] = false;
        echo "<p>Thank you, $name. You have successfully booked Room $room with preference: $preference. Your check-in time is $checkin.</p>";
    } else {
        echo "<p>Sorry, Room $room is already booked. Please choose another room.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color:rgba(0, 0, 0, 0);
            box-shadow: 0 4px 6px rgb(255, 255, 255);
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white; 
            color: black;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .payment-info {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<h2>Book a Room</h2>

<form method="POST" action="">
    <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div>
        <label for="room">Room Number (1-20)</label>
        <input type="number" id="room" name="room" min="1" max="20" required>
    </div>

    <div>
        <label for="preference">Preference</label>
        <select id="preference" name="preference">
            <option value="bed">Bed only - 1000</option>
            <option value="bed-breakfast">Bed and Breakfast - 1500</option>
        </select>
    </div>

    <div>
        <label for="checkin">Anticipated Check-in Time</label>
        <input type="time" id="checkin" name="checkin" required>
    </div>

    <button type="submit">Book Now</button>
</form>

<div class="payment-info">
    <p>Payment Details: Till Number 123456</p>
</div>

</body>
</html>
