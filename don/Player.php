<?php
require_once "DB.php";

class Player {
    public function createPlayer($user_id, $age, $nationality, $club, $country, $salary) {
        $conn = DB::connect();
        $stmt = $conn->prepare("INSERT INTO players (user_id, age, nationality, current_club, country, salary) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $age, $nationality, $club, $country, $salary]);
    }

    public function updatePlayer($user_id, $age, $club, $salary) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE players SET age=?, current_club=?, salary=? WHERE user_id=?");
        return $stmt->execute([$age, $club, $salary, $user_id]);
    }
}
?>
