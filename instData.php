<?php
$servername = "localhost:3308";
$username = "root";
$password = "caleb";
$dbname = "api_proj";

try {
  $conn = new PDO("mysql:host=$servername;dbname=api_proj", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Students (firstname, lastname, mobile, email)
  VALUES ('Evelyn', 'Mutoro', '+254765086231', 'e.mutoro@gmail.com')";
  // use exec() because no results are returned
  $conn->exec($sql);
  echo "New record created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>