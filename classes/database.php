<?php
class Database {
    private $host = "localhost"; // Update with your database host
    private $db_name = "your_database_name"; // Update with your database name
    private $username = "your_username"; // Update with your database username
    private $password = "your_password"; // Update with your database password
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        } catch (mysqli_sql_exception $exception) {
            die("Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
