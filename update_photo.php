<?php
session_start();
include 'db_connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update_photo') {
    $oldSrc = $_POST['old_src'];
    $newSrc = $_POST['new_src'];
    $category = $_POST['category']; // Optional

    // Logic to update the photo URL in the database
    $query = "UPDATE photos SET image_url = ? WHERE image_url = ?"; // Change 'photos' to your table name
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $newSrc, $oldSrc); // Bind parameters
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Photo updated successfully.']);
    } else {
        echo json_encode(['message' => 'Error updating photo.']);
    }
}
?>
