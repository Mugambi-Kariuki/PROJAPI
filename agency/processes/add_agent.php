<?php
include '../classes/database.php'; // Update the path to your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $charge_fee = $_POST['charge_fee'];
    $nationality = $_POST['nationality'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate charge fee
    if ($charge_fee < 0) {
        echo "Charge fee must be a positive value.";
        exit();
    }

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Update the column names to match your database schema
        $stmt = $conn->prepare("INSERT INTO agents (full_name, email, contact_number, charge_fee, nationality, password_hash) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $contact_number, $charge_fee, $nationality, $password);

        if ($stmt->execute()) {
            echo "Agent added successfully.";
        } else {
            throw new Exception("Failed to add agent: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "An error occurred: " . $e->getMessage();
    } finally {
        $conn->close();
    }
} else {
    echo "Invalid request method.";
}
?>