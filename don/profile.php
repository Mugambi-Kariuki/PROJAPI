<?php
session_start();
require 'DB.php';

$user_id = $_SESSION['user']['id'];
$player = $conn->query("SELECT * FROM players WHERE user_id = $user_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $age = $_POST['age'];
    $nationality = $_POST['nationality'];
    $current_club = $_POST['current_club'];
    $country = $_POST['country'];
    $salary = $_POST['salary'];

    // Image Upload
    $image = $_FILES['profile_image']['name'];
    $imagePath = "uploads/" . basename($image);
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $imagePath);

    $stmt = $conn->prepare("UPDATE players SET age=?, nationality=?, current_club=?, country=?, salary=?, profile_image=? WHERE user_id=?");
    $stmt->bind_param("isssdsi", $age, $nationality, $current_club, $country, $salary, $imagePath, $user_id);
    $stmt->execute();

    header("Location: profile.php?success=Profile updated!");
}
?>

<form action="profile.php" method="post" enctype="multipart/form-data">
    <input type="text" name="age" value="<?php echo $player['age']; ?>">
    <input type="text" name="nationality" value="<?php echo $player['nationality']; ?>">
    <input type="text" name="current_club" value="<?php echo $player['current_club']; ?>">
    <input type="text" name="country" value="<?php echo $player['country']; ?>">
    <input type="text" name="salary" value="<?php echo $player['salary']; ?>">
    <input type="file" name="profile_image">
    <button type="submit">Update</button>
</form>
