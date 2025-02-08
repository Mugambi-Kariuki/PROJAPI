<?php
require_once "../config.php";

class Register {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function registerUser($name, $email, $password, $age, $nationality, $current_club, $salary) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        // Insert user into the user table first to get the user_id
        $user_query = "INSERT INTO user (name, email, password, role, verification_code) 
                       VALUES (:name, :email, :password, 'player', :verification_code)";
        $user_stmt = $this->db->prepare($user_query);
        $user_stmt->bindParam(":name", $name);
        $user_stmt->bindParam(":email", $email);
        $user_stmt->bindParam(":password", $hashed_password);
        $user_stmt->bindParam(":verification_code", $verification_code);

        if ($user_stmt->execute()) {
            // Get the new user_id
            $user_id = $this->db->lastInsertId();

            // Now insert into the player table
            $player_query = "INSERT INTO player (user_id, name, email, password, verification_code, age, nationality, current_club, salary) 
                             VALUES (:user_id, :name, :email, :password, :verification_code, :age, :nationality, :current_club, :salary)";
            $player_stmt = $this->db->prepare($player_query);
            $player_stmt->bindParam(":user_id", $user_id);
            $player_stmt->bindParam(":name", $name);
            $player_stmt->bindParam(":email", $email);
            $player_stmt->bindParam(":password", $hashed_password);
            $player_stmt->bindParam(":verification_code", $verification_code);
            $player_stmt->bindParam(":age", $age);
            $player_stmt->bindParam(":nationality", $nationality);
            $player_stmt->bindParam(":current_club", $current_club);
            $player_stmt->bindParam(":salary", $salary);

            return $player_stmt->execute();
        } else {
            return false;
        }
    }
}

// Process Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $age = $_POST["age"];
    $nationality = $_POST["nationality"];
    $current_club = $_POST["current_club"];
    $salary = $_POST["salary"];

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    $register = new Register();
    if ($register->registerUser($name, $email, $password, $age, $nationality, $current_club, $salary)) {
        header("Location: ../dashboard/player_dashboard.php");
        exit();
    } else {
        echo "Error registering player.";
    }
}
?>
