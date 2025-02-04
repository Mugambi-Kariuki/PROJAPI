<?php
require_once "../config.php";

class Register {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function registerUser($name, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
        // Generate a 6-digit verification code
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
    
        $query = "INSERT INTO user (name, email, password, role, verification_code, email_verified_at) 
                  VALUES (:name, :email, :password, :role, :verification_code, NULL)";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":verification_code", $verification_code);
    
        return $stmt->execute();
    }
    
}

// Process Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $register = new Register();
    if ($register->registerUser($name, $email, $password, $role)) {
        header("Location: ../forms/verification.php");
        exit();
    } else {
        echo "Error registering user.";
    }
}
?>