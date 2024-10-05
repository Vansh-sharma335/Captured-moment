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

    // Fetch current gallery content
    $sql = "SELECT * FROM gallery ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $gallery_content = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit; // Exit if the connection fails
}

// Handle deletion of an image
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $delete_sql = "DELETE FROM gallery WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->execute(['id' => $id]);

    // Optionally, delete the image file from the server
    $delete_image_sql = "SELECT image_path FROM gallery WHERE id = :id";
    $image_stmt = $pdo->prepare($delete_image_sql);
    $image_stmt->execute(['id' => $id]);
    $image_data = $image_stmt->fetch(PDO::FETCH_ASSOC);
    if ($image_data) {
        unlink($image_data['image_path']); // Delete image from server
    }

    // Set success message
    $_SESSION['message'] = "Image deleted successfully!";
    header("Location: manage_gallery.php"); // Refresh page after deletion
    exit;
}

// Handle addition of new content
if (isset($_POST['add'])) {
    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $description = $_POST['description'];

            $insert_sql = "INSERT INTO gallery (image_path, description, created_at) VALUES (:image_path, :description, NOW())";
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute(['image_path' => $image_path, 'description' => $description]);

            // Set success message
            $_SESSION['message'] = "Image added successfully!";
            header("Location: manage_gallery.php"); // Redirect to manage gallery page
            exit;
        } else {
            $_SESSION['error'] = "There was an error uploading the file.";
        }
    } else {
        $_SESSION['error'] = "File upload error: " . $_FILES['image']['error']; // Display specific upload error
    }
}

// Handle updating content
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $description = $_POST['description'];

    $update_sql = "UPDATE gallery SET description = :description WHERE id = :id";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute(['description' => $description, 'id' => $id]);

    // Set success message
    $_SESSION['message'] = "Description updated successfully!";
    header("Location: manage_gallery.php"); // Refresh page after update
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add some styles for the dropdown */
        .dropdown {
            position: absolute;
            display: none;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        
        .dropdown button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border: none;
            width: 100%;
            text-align: left;
            background: transparent;
        }

        .dropdown button:hover {
            background-color: #f1f1f1;
        }

        .gallery-content-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px 0;
        }

        .gallery-content-item {
            width: calc(33.33% - 20px); /* Three items per row */
            margin: 10px;
            text-align: center;
            position: relative; /* Needed for absolute positioning of dropdown */
        }

        .gallery-content-item img {
            width: 100%; /* Ensure image fits */
            height: auto; /* Maintain aspect ratio */
            border-radius: 5px; /* Optional: Add rounded corners */
        }

        /* Styles for the 3-dot menu */
        .three-dots {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 20px; /* Adjust as needed */
            color: #333;
        }

        .description-area {
            display: none; /* Hide by default */
            margin: 10px 0;
        }

        /* Styles for the success message */
        .message, .error {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            color: white;
        }
        .message {
            background-color: #4CAF50; /* Green */
        }
        .error {
            background-color: #f44336; /* Red */
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage Gallery</h1>
        <nav>
        <a href="index.php">Home</a>
            <a href="admin_panel.php">Admin Panel</a>
            <a href="manage_gallery.php">Manage Gallery</a>
            <a href="manage_home.php">Manage Home Page</a>
            <a href="create_portfolio.php">Create Portfolio</a>
            <a href="view_messages.php">View Messages</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> 
        <?php endif; ?>
    </nav>
    </header>

    <section>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Clear message after displaying
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Clear error after displaying
                ?>
            </div>
        <?php endif; ?>

        <h2>Current Gallery Content</h2>
        <div class="gallery-content-grid">
            <?php foreach ($gallery_content as $content): ?>
                <div class="gallery-content-item">
                    <img src="<?php echo htmlspecialchars($content['image_path']); ?>" alt="Gallery Image">
                    <button class="three-dots" onclick="toggleDropdown(event)">⋮</button>
                    <div class="dropdown">
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                            <button type="button" onclick="editDescription(this)">Edit Description</button>
                            <button type="submit" name="delete" style="background: red; color: white;">Delete</button>
                        </form>
                    </div>
                    <div class="description-area">
                        <form method="POST" style="margin: 0;">
                            <textarea name="description" style="width: 100%; height: 100px;"><?php echo htmlspecialchars($content['description']); ?></textarea>
                            <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                            <button type="submit" name="update" style="width: 100%;">Update Description</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Add New Image</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <textarea name="description" placeholder="Description" required style="width: 100%; height: 100px;"></textarea>
            <button type="submit" name="add">Add Image</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Captured Moments</p>
    </footer>

    <script>
        function toggleDropdown(event) {
            const dropdown = event.target.nextElementSibling;
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        function editDescription(button) {
            const descriptionArea = button.closest('.gallery-content-item').querySelector('.description-area');
            descriptionArea.style.display = 'block'; // Show the description area for editing
        }

        // Close dropdowns if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.three-dots')) {
                const dropdowns = document.getElementsByClassName("dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
