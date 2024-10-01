<?php
$servername = "localhost";
$dbusername = "root";  // Replace with your MySQL username
$password = "";  // Replace with your MySQL password
$dbname = "project_1";

// Create connection
try {
    $conn = new PDO("mysql:servername=$servername;dbname=$dbname", $dbusername, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection Done!";
}
// Check connection
catch (PDOException $err) {
    die("Connection failed:" . $err->getMessage());
}
// echo "DB_CONNECTED";