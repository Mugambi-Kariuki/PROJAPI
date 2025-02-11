<?php
session_start();
require_once "classes/Database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = trim($_POST['code']);
    $user_id = $_SESSION['user_id'];

    $db = new Database();
    $conn = $db->getConnection();

    // Check if the code matches
    $stmt = $conn->prepare("SELECT * FROM user_verification WHERE user_id = ? AND verification_code = ?");
    $stmt->execute([$user_id, $code]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Mark user as verified
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
        $stmt->execute([$user_id]);

        // Delete verification code
        $stmt = $conn->prepare("DELETE FROM user_verification WHERE user_id = ?");
        $stmt->execute([$user_id]);

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid verification code.";
    }
}
?>

<form method="post">
    <input type="text" name="code" placeholder="Enter Verification Code" required>
    <button type="submit">Verify</button>
</form>
