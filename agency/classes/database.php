<?php
class Database {
    private $host = "localhost";
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
        }
    }

    // Get the connection instance
    public function getConnection() {
        return $this->conn;
    }

    // Close the connection when the object is destroyed
    public function __destruct() {
        $this->conn->close();
    }
}
?>
