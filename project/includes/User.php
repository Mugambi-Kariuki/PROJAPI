<?php
require_once 'database.php';

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $verification_code = rand(100000, 999999);

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $verification_code);

        if ($stmt->execute()) {
            return $verification_code;
        }
        return false;
    }

    public function verifyUser($email, $code) {
        $stmt = $this->conn->prepare("SELECT verification_code FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($stored_code);
        $stmt->fetch();
        $stmt->close();

        if ($stored_code == $code) {
            $stmt = $this->conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
            $stmt->bind_param("s", $email);
            return $stmt->execute();
        }
        return false;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, password, verified FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hashed_password, $verified);
        $stmt->fetch();

        if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
            if ($verified == 1) {
                return ['id' => $id, 'username' => $username];
            } else {
                return "Not Verified";
            }
        }
        return false;
    }
}
?>
