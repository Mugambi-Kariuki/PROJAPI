<?php
class Database {
    private $host = "localhost:3308";
    private $username = "root";
    private $password = "caleb";
    private $db_name = "GOATS";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        } catch (Exception $e) {
            error_log("Connection error: " . $e->getMessage());
            die("Connection error: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>
