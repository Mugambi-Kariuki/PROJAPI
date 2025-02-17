<?php
require_once "../classes/database.php";

class User {
    private $conn;
    private $table_name = "user"; // Referring to the 'user' table

    // Constructor initializes the database connection
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();

        if (!$this->conn) {
            error_log("Database connection failed.");
            throw new Exception("Database connection failed.");
        }

        if ($this->conn->connect_error) {
            error_log("Connection error: " . $this->conn->connect_error);
            throw new Exception("Connection error: " . $this->conn->connect_error);
        }
    }

    // Login function to check credentials
    public function login($email, $password) {
        try {
            // Check if the connection is still valid
            if (!$this->conn) {
                throw new Exception("Database connection is closed.");
            }

            // Prepare the SQL query to get user data based on the provided email
            $query = "SELECT id, email, password, is_verified FROM " . $this->table_name . " WHERE email = ?";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                error_log("Prepare statement failed: " . $this->conn->error);
                throw new Exception("Prepare statement failed: " . $this->conn->error);
            }

            // Bind email parameter to the query
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Get the result of the query
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // Check if the user exists and verify the password
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
            return ['status' => 'error', 'message' => 'An error occurred during login: ' . $e->getMessage()];
        }
    }
}
?>
