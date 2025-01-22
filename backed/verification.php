<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <style>
        #container {
            margin: 40px auto;
            width: 440px;
            padding: 20px;
            border: 1px solid black;
        }
        input[type=text] {
            width: 300px;
            height: 20px;
            padding: 10px;
        }
        label {
            font-size: 20px;
            font-weight: bold;
        }
        form {
            margin-left: 50px;
        }
        input[type=submit] {
            width: 100px;
            background-color: blue;
            border: 1px solid blue;
            color: white;
            font-weight: bold;
            padding: 7px;
            margin-left: 110px;
        }
        input[type=submit]:hover {
            background-color: purple;
            cursor: pointer;
            border: 1px solid purple;
        }
    </style>
</head>
<body>
    <div id="container">
        <form action="otp_verification_process.php" method="POST">
            <label for="otp">Enter Verification Code (OTP):</label><br>
            <input type="text" name="otp" placeholder="Enter Your OTP" required><br><br>
            <input type="submit" name="verify" value="Verify"><br><br>
        </form>
    </div>
</body>
</html>
