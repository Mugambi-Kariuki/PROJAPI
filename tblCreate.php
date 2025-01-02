<?php
$servername = "localhost:3308";
$username = "root";
$password = "caleb";
$dbname = "api_proj";

try {
  $conn = new PDO("mysql:host=$servername;dbname=api_proj", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // sql to create table
  $sql = "CREATE TABLE User (
  username VARCHAR(50) NOT NULL UNIQUE,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  email VARCHAR(50),
  password VARCHAR(255) NOT NULL,
  mobile VARCHAR(15),
  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";

  // use exec() because no results are returned
  $conn->exec($sql);
  echo "Table User created successfully";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
?>