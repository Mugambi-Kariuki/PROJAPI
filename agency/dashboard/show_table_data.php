<?php
include '../classes/database.php'; // Update the path to your database connection file

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access.");
}

if (isset($_GET['table'])) {
    $table = $_GET['table'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Fetch table data
        $result = $conn->query("SELECT * FROM $table");
        if (!$result) {
            throw new Exception("Failed to fetch table data: " . $conn->error);
        }

        echo "<h2>Table: $table</h2>";
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead class='table-dark'><tr>";

        // Fetch table headers
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            echo "<th>{$field->name}</th>";
        }
        echo "</tr></thead><tbody>";

        // Fetch table rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr class='table-light'>";
            foreach ($row as $cell) {
                echo "<td>$cell</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";

    } catch (Exception $e) {
        error_log($e->getMessage());
        die("An error occurred: " . $e->getMessage());
    } finally {
        $conn->close(); // Ensure the connection is closed
    }
} else {
    die("Invalid request.");
}
?>
