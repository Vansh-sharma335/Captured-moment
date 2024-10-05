<?php
// Database connection details
$host = 'localhost'; // Adjust if needed
$dbname = 'captured_moments'; // Your database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
