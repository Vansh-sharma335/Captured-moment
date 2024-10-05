<?php
session_start();

// Database connection variables
$host = 'localhost';
$dbname = 'captured_moments';
$username = 'root';
$password = '';

// Initialize response
$response = ['success' => false, 'message' => ''];

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword]);

        // Set response message
        $response['success'] = true;
        $response['message'] = 'Account created successfully! Please log in.';
    }
} catch (PDOException $e) {
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
