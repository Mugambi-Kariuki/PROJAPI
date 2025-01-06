<?php
class dbConnection {
    private $connection;
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pass;
    private $db_name;

    // Constructor to initialize the database parameters and establish connection
    public function __construct($db_type = 'PDO', $db_host = 'localhost', $db_port = '3308', $db_user = 'root', $db_pass = 'caleb', $db_name = 'api_proj') {
        $this->db_type = $db_type;
        $this->db_host = $db_host;
        $this->db_port = $db_port;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
        $this->connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name);
    }

    // Method to establish the connection
    public function connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        if ($db_type == 'PDO') {
            try {
                // Forming the DSN (Data Source Name) for PDO
                $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name";
                // Creating the PDO instance
                $this->connection = new PDO($dsn, $db_user, $db_pass);
                // Set PDO error mode to exception
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connection successful."; // Connection success message
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage(); // Handle errors
            }
        }
    }

    // Method to get the database connection
    public function getConnection() {
        return $this->connection;
    }
}

// Example usage
$database = new dbConnection();
$conn = $database->getConnection();
?>
