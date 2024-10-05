<?php
$conn = new mysqli('localhost', 'root', '', 'captured_moments');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>
