<?php
require_once "../classes/database.php";

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $email);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && password_verify($password, $row['password'])) {
                return [
                    'status' => 'success',
                    'user_id' => $row['id'],
                    'email' => $row['email'],
                    'is_verified' => $row['is_verified']
                ];
            } else {
                return ['status' => 'error', 'message' => 'Invalid credentials'];
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'An error occurred during login'];
        }
    }
}
?>
