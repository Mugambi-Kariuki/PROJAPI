<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Email Verification</h2>
        <form action="confirm_verification.php" method="POST">
            <input type="text" name="verification_code" placeholder="Enter Code" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
