<?php

$host = "localhost:3308";
$dbname = "login_db";
$username = "root";
$password = "caleb";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;