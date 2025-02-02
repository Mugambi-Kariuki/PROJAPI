<?php
require_once "DB.php";

class User {
    public function register($name, $email, $password, $role) {
        $conn = DB::connect();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $hashed_password, $role]);
    }

    public function verifyEmail($email) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public function login($email, $password) {
        $conn = DB::connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
