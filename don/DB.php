<?php
class DB {
    private static $host = "localhost:3308";
    private static $dbname = "donn_carlo_agency";
    private static $username = "root";
    private static $password = "caleb";
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO("mysql:host=".self::$host.";dbname=".self::$dbname, self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
