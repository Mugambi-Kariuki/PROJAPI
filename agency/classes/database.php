<?php
class Database {
    private $host = "localhost:3308";
    private $username = "root";
    private $password = "caleb";
    private $dbname = "GOATs";
    private $conn;

    // Constructor initializes the database connection
    public function __construct() {
        $this->connect();
    }

    // Establish the database connection
    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        } else {
            echo "Database connection successful.";
        }
    }

    // Get the connection instance
    public function getConnection() {
        return $this->conn;
    }

    // Optionally, you can explicitly close the connection when needed (not automatically in __destruct)
    public function closeConnection() {
        $this->conn->close();
    }
}
?>
