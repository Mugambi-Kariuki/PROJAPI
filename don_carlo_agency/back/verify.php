<?php
require_once "../config.php";
require "../mailer.php";

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $db = new Database();
    $conn = $db->getConnection();

    $query = "UPDATE user SET verified = 1 WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email);
    
    if ($stmt->execute()) {
        header("Location: ../forms/user login.php");
        exit();
    } else {
        echo "Verification failed.";
    }
}
?>