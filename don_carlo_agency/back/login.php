<?php
require_once "../config.php";

class Login {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function authenticateUser($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            if ($user["verified"] == 1) {
                session_start();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                return true;
            } else {
                echo "Please verify your email first.";
                return false;
            }
        } else {
            echo "Invalid credentials.";
            return false;
        }
    }
}

// Process Login Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $login = new Login();
    if ($login->authenticateUser($email, $password)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Login failed.";
    }
}
?>
