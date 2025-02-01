<?php
require_once "../config.php";

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $db = new Database();
    $conn = $db->getConnection();

    $query = "UPDATE users SET verified = 1 WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    
    if ($stmt->execute()) {
        echo "Account verified successfully! <a href='login.php'>Login</a>";
    } else {
        echo "Verification failed.";
    }
}
?>
