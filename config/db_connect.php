<?php
$servername = "localhost"; 
// Change if necessary
$username = "root"; 
// Your MySQL username 
$password = ""; 
// Your MySQL password (leave empty for XAMPP) 
$dbname = "student_management"; 
// Create connection 
$conn = new mysqli(hostname: $servername, username: $username, password: $password, database:  $dbname);

// Check connection 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}

?>