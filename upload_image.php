<?php
session_start();

// Database connection variables
$host = 'localhost';
$dbname = 'captured_moments';
$username = 'root';
$password = ''; 

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && isset($_POST['portfolio_id'])) {
        $portfolioId = $_POST['portfolio_id'];
        $image = $_FILES['image'];

        // Check for upload errors
        if ($image['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error.');
        }

        // Generate a unique filename
        $filename = uniqid() . '_' . basename($image['name']);
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . $filename;

        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move the uploaded file
        if (!move_uploaded_file($image['tmp_name'], $uploadFile)) {
            throw new Exception('Failed to move uploaded file.');
        }

        // Insert image path into the database
        $sql = "INSERT INTO portfolio_images (portfolio_id, image_path) VALUES (:portfolio_id, :image_path)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'portfolio_id' => $portfolioId,
            'image_path' => $uploadFile
        ]);

        header('Location: portfolio_details.php?id=' . $portfolioId);
        exit();
    } else {
        throw new Exception('Invalid request.');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
