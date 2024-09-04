<?php
$servername = "localhost";  // Database server (usually 'localhost')
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "surat";    // Your database name
$port = 3307;               // MySQL port number, updated to 3307

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
