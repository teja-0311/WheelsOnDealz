<?php
$servername = "localhost"; // Default for XAMPP
$username = "root";        // Default username for XAMPP
$password = "";            // Default password for XAMPP (empty string)
$dbname = "23bcs091";   // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
