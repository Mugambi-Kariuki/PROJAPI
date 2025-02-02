<?php
require_once "DB.php";

class Agent {
    public function createAgent($user_id, $experience, $charges) {
        $conn = DB::connect();
        $stmt = $conn->prepare("INSERT INTO agents (user_id, experience, charges) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $experience, $charges]);
    }

    public function updateAgentStatus($user_id, $status) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE agents SET status=? WHERE user_id=?");
        return $stmt->execute([$status, $user_id]);
    }

    public function updateAgentSuccessRate($user_id, $success, $failed) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE agents SET success_transfers=?, failed_transfers=? WHERE user_id=?");
        return $stmt->execute([$success, $failed, $user_id]);
    }
}
?>
