<?php
class Database {
    private $host = "localhost:3308";
    private $user = "root";
    private $pass = "caleb";
    private $dbname = "user_management";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Database Connection Error: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
