<?php
// Include database connection
include 'db_connect.php';

// Start the session
session_start();

// Handle form submissions for updating content and uploading images
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle category image upload
    if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {
        $category_id = $_POST['category_id'];
        $image_name = $_FILES['category_image']['name'];
        $target_dir = "uploads/categories/"; // Make sure this directory exists
        $target_file = $target_dir . basename($image_name);
        
        // Validate and upload the image
        if (move_uploaded_file($_FILES['category_image']['tmp_name'], $target_file)) {
            // Insert into the database
            $sql = "INSERT INTO photo_gallery (url, large_url, description, category_id) VALUES (?, ?, ?, ?)"; // Change table name here
            $stmt = $conn->prepare($sql);
            $large_url = $target_file; // Assuming large_url is the same as the uploaded file
            $description = "Image uploaded to category"; // Modify this for real descriptions
            $stmt->bind_param('sssi', $image_name, $large_url, $description, $category_id);
            $stmt->execute();
            echo "<script>alert('Successfully uploaded the category image.');</script>";
        } else {
            echo "<script>alert('Failed to upload the category image.');</script>";
        }
    }

    // Handle latest photo image upload
    if (isset($_FILES['latest_photo_image']) && $_FILES['latest_photo_image']['error'] == 0) {
        $image_name = $_FILES['latest_photo_image']['name'];
        $target_dir = "uploads/latest_photos/"; // Make sure this directory exists
        $target_file = $target_dir . basename($image_name);
        
        // Validate and upload the image
        if (move_uploaded_file($_FILES['latest_photo_image']['tmp_name'], $target_file)) {
            // Insert latest photo into the database
            $sql = "INSERT INTO photo_gallery (url, large_url, description, category_id) VALUES (?, ?, ?, ?)"; // Change table name here
            $stmt = $conn->prepare($sql);
            $large_url = $target_file; // Assuming large_url is the same as the uploaded file
            $description = "Latest photo uploaded"; // Modify for real descriptions
            $category_id = 1; // Or any default category ID for latest photos
            $stmt->bind_param('sssi', $image_name, $large_url, $description, $category_id);
            $stmt->execute();
            echo "<script>alert('Successfully uploaded the latest photo.');</script>";
        } else {
            echo "<script>alert('Failed to upload the latest photo.');</script>";
        }
    }

    // Update latest photos if provided
    if (isset($_POST['latest_photos'])) {
        $latest_photos = $_POST['latest_photos'];
        $sql = "UPDATE site_content SET latest_photos = ? WHERE id = 1"; // Update latest photos
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $latest_photos);
        $stmt->execute();
        echo "<script>alert('Successfully updated the latest photos section.');</script>";
    }

    // Update about us content if provided
    if (isset($_POST['about_us'])) {
        $about_us = $_POST['about_us'];
        $sql = "UPDATE site_content SET about_us = ? WHERE id = 1"; // Update about us content
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $about_us);
        $stmt->execute();
        echo "<script>alert('Successfully updated the About Us section.');</script>";
    }

    // Handle delete image request
    if (isset($_POST['delete_image_id'])) {
        $image_id = $_POST['delete_image_id'];
        $sql = "DELETE FROM photo_gallery WHERE id = ?"; // Change table name here
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $image_id);
        $stmt->execute();
        echo "<script>alert('Successfully deleted the image.');</script>";
    }

    // Redirect back to the same page to show updates
    header('Location: manage_home.php');
    exit();
}

// Fetch site content
$sql = "SELECT latest_photos, about_us FROM site_content WHERE id = 1"; // Assuming there's only one row
$result = $conn->query($sql);
$content = $result->fetch_assoc() ?? ['latest_photos' => '', 'about_us' => '']; // Prevent null access

// Fetch categories
$sql_categories = "SELECT * FROM categories"; // Fetching categories from the database
$result_categories = $conn->query($sql_categories);

// Fetch images
$sql_images = "SELECT * FROM photo_gallery"; // Change table name here
$result_images = $conn->query($sql_images);

// Fetch about us content
$sql_about_us = "SELECT description FROM homepage_content WHERE section = 'about'"; // Assuming a separate table for about us
$result_about_us = $conn->query($sql_about_us);
$about_content = $result_about_us->fetch_assoc() ?? ['description' => 'No description available.']; // Prevent null access
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Home - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <h1>Manage Captured Moments</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="admin_panel.php">Admin Panel</a>
        <a href="manage_gallery.php">Manage Gallery</a>
        <a href="manage_home.php">Manage Home Page</a>
        <a href="create_portfolio.php">Create Portfolio</a>
        <a href="view_messages.php">View Messages</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</header>

<section>
    <h2>Upload Image to Photography Category</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="category">Choose Category:</label>
        <select name="category_id" required>
            <?php while ($row = $result_categories->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php endwhile; ?>
        </select>
        <input type="file" name="category_image" accept="image/*" required>
        <button type="submit">Upload Image</button>
    </form>
</section>

<section>
    <h2>Upload Image to Latest Photos</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="latest_photo_image" accept="image/*" required>
        <button type="submit">Upload Latest Photo</button>
    </form>
</section>

<section>
    <h2>About Us</h2>
    <form method="POST">
        <textarea name="about_us" rows="5" required><?php echo htmlspecialchars($about_content['description']); ?></textarea>
        <button type="submit">Update About Us</button>
    </form>
</section>

<section>
    <h2>Latest Photos Section</h2>
    <form method="POST">
        <textarea name="latest_photos" rows="5" required><?php echo htmlspecialchars($content['latest_photos']); ?></textarea>
        <button type="submit">Update Latest Photos</button>
    </form>
</section>

<section>
    <h2>Manage Images</h2>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_images->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($row['url']); ?>" alt="Image" width="100"></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" required>
                            <input type="hidden" name="image_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="update_description">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_image_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this image?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

</body>
</html>
