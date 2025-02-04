<?php
require_once "../config.php";

class Verification {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function verifyCode($email, $code) {
        $query = "SELECT verification_code FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['verification_code'] === $code) {
            $updateQuery = "UPDATE user SET email_verified_at = NOW(), verification_code = NULL WHERE email = :email";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(":email", $email);
            return $updateStmt->execute();
        }
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $code = $_POST["code"];

    $verification = new Verification();
    if ($verification->verifyCode($email, $code)) {
        header("Location: ../forms/user login.php");
        exit();
    } else {
        echo "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h2>Email Verification</h2>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="code">Verification Code:</label>
        <input type="text" name="code" required>
        <br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>