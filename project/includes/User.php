<?php
require_once 'Database.php';

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 🔹 Register User
    public function register($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_code = rand(100000, 999999);

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $verification_code);

        if ($stmt->execute()) {
            return $verification_code;
        } else {
            return false;
        }
    }

    // 🔹 Verify User
    public function verifyUser($email, $code) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND verification_code = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt = $this->conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return true;
        } else {
            return false;
        }
    }
}
?>
