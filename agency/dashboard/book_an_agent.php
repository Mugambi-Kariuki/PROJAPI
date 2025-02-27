<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/database.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

// Ensure the agent is selected
if (!isset($_GET['agent_id'])) {
    echo "No agent selected.";
    exit;
}

$agent_id = $_GET['agent_id'];

// Fetch agent details
function getAgentDetails($agent_id) {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $query = "SELECT agent_id, full_name FROM agents WHERE agent_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("Agent not found.");
    }
    return $result->fetch_assoc();
}

$agent = getAgentDetails($agent_id);

// Process booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $agent_id = $_POST['agent_id'];
    $target_club = $_POST['target_club'];
    $years = $_POST['years'];
    $expected_salary = $_POST['expected_salary'];

    $database = new Database();
    $conn = $database->getConnection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "INSERT INTO booking (agent_id, user_id, target_club, years, expected_salary) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisid", $agent_id, $user_id, $target_club, $years, $expected_salary);

    if ($stmt->execute()) {
        echo "Booking successful.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Agent</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Book an Agent</h2>
    <form id="bookingForm">
        <label for="agent">Selected Agent:</label>
        <input type="text" name="agent_name" value="<?php echo htmlspecialchars($agent['full_name']); ?>" readonly><br>
        <input type="hidden" name="agent_id" value="<?php echo $agent['agent_id']; ?>">
        
        <label for="club">Select Target Club:</label>
        <input type="text" id="clubSearch" placeholder="Search for a club...">
        <div id="clubResults"></div>
        <input type="hidden" name="target_club" id="targetClub" required><br>
        
        <label for="years">Contract Years:</label>
        <input type="number" name="years" min="1" required><br>
        
        <label for="expected_salary">Expected Salary:</label>
        <input type="number" name="expected_salary" min="1" required><br>
        
        <button type="submit">Book Agent</button>
    </form>
    
    <script>
        $(document).ready(function() {
            $('#clubSearch').on('keyup', function() {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: 'search_clubs.php',
                        method: 'POST',
                        data: { search: query },
                        success: function(response) {
                            let clubs = JSON.parse(response);
                            let resultsHtml = '';
                            clubs.forEach(function(club) {
                                resultsHtml += '<button type="button" class="club-item">' + club.name + '</button>';
                            });
                            $('#clubResults').html(resultsHtml);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error fetching clubs:', textStatus, errorThrown);
                        }
                    });
                } else {
                    $('#clubResults').html('');
                }
            });
            
            $(document).on('click', '.club-item', function() {
                let clubName = $(this).text();
                $('#clubSearch').val(clubName);
                $('#targetClub').val(clubName);
                $('#clubResults').html('');
            });
        });
        
        $('#bookingForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error processing booking:', textStatus, errorThrown);
                    alert('Error processing booking. Please try again.');
                }
            });
        });
    </script>
</body>
</html>