<?php
session_start();

// Initialize unread count variable
$unreadCount = 0; // Default value

// Database connection variables
$host = 'localhost';
$dbname = 'captured_moments';
$username = 'root';  // Replace with your database username
$password = '';      // Replace with your database password

// Initialize messages
$portfolioMessage = "";
$editMode = false; // Set the edit mode variable
$portfolioData = []; // Variable to hold portfolio data for editing

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check for unread messages (if you have a table for messages)
    // Uncomment and modify this section if you are tracking unread messages
    /*
    $stmt = $pdo->query("SELECT COUNT(*) FROM messages WHERE status = 'unread'");
    $unreadCount = $stmt->fetchColumn();
    */

    // Handle form submission for creating a new portfolio
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form data
        $title = $_POST['title'];
        $description = $_POST['description'];
        $experience = $_POST['experience'];
        $category = $_POST['category'];
        $portfolioId = $_POST['portfolio_id'] ?? null; // Get portfolio ID for editing

        // File handling
        $targetDir = "uploads/";
        $uploadedFiles = $_FILES['portfolio_images'];
        $images = [];

        for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
            $targetFile = $targetDir . basename($uploadedFiles["name"][$i]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if image file is a valid image type
            $check = getimagesize($uploadedFiles["tmp_name"][$i]);
            if ($check !== false) {
                // Allow certain file formats
                $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($imageFileType, $allowedFormats)) {
                    if (move_uploaded_file($uploadedFiles["tmp_name"][$i], $targetFile)) {
                        $images[] = $targetFile; // Store the path of the uploaded image
                    } else {
                        $portfolioMessage = "Error uploading your image.";
                        break;
                    }
                } else {
                    $portfolioMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
                    break;
                }
            } else {
                $portfolioMessage = "One of the files is not an image.";
                break;
            }
        }

        if (empty($portfolioMessage)) {
            if ($portfolioId) { // Edit existing portfolio
                // Update portfolio data in the database
                $sql = "UPDATE portfolios SET title = :title, description = :description, 
                        experience = :experience, category = :category, updated_at = NOW() 
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['title' => $title, 'description' => $description,
                                'experience' => $experience, 'category' => $category, 'id' => $portfolioId]);

                // Add new images to the portfolio
                foreach ($images as $imagePath) {
                    $sqlImage = "INSERT INTO portfolio_images (portfolio_id, image_path) VALUES (:portfolio_id, :image_path)";
                    $stmtImage = $pdo->prepare($sqlImage);
                    $stmtImage->execute(['portfolio_id' => $portfolioId, 'image_path' => $imagePath]);
                }

                $portfolioMessage = "Your portfolio has been updated successfully.";
            } else { // Create new portfolio
                // Insert portfolio data into the database
                foreach ($images as $imagePath) {
                    $sql = "INSERT INTO portfolios (title, description, experience, image_path, category, created_at)
                            VALUES (:title, :description, :experience, :image_path, :category, NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['title' => $title, 'description' => $description, 'experience' => $experience,
                                    'image_path' => $imagePath, 'category' => $category]);
                }
                $portfolioMessage = "Your portfolio has been created successfully.";
            }
        }
    }

    // Handle portfolio deletion
    if (isset($_GET['delete_id'])) {
        $deleteId = intval($_GET['delete_id']);
        if ($deleteId > 0) {
            // Delete associated images first
            $deleteImagesStmt = $pdo->prepare("DELETE FROM portfolio_images WHERE portfolio_id = :id");
            $deleteImagesStmt->execute(['id' => $deleteId]);

            // Now delete the portfolio
            $deletePortfolioStmt = $pdo->prepare("DELETE FROM portfolios WHERE id = :id");
            $deletePortfolioStmt->execute(['id' => $deleteId]);

            if ($deletePortfolioStmt->rowCount() > 0) {
                $portfolioMessage = "Portfolio deleted successfully.";
            } else {
                $portfolioMessage = "Portfolio not found or unable to delete.";
            }
        } else {
            $portfolioMessage = "Invalid portfolio ID.";
        }
    }

    // Handle portfolio editing
    if (isset($_GET['edit_id'])) {
        $editId = intval($_GET['edit_id']);
        if ($editId > 0) {
            // Fetch the portfolio data
            $stmt = $pdo->prepare("SELECT * FROM portfolios WHERE id = :id");
            $stmt->execute(['id' => $editId]);
            $portfolioData = $stmt->fetch(PDO::FETCH_ASSOC);
            $editMode = true; // Enable edit mode
        }
    }

    // Fetch all portfolios
    $stmt = $pdo->prepare("SELECT * FROM portfolios ORDER BY created_at DESC");
    $stmt->execute();
    $portfolios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $portfolioMessage = "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create/Edit Portfolio - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS only for Portfolio List */
        #portfolio-list {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #000000;;
        }
        img {
            max-width: 100px;
            max-height: 75px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Captured Moments - Admin Panel</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="admin_panel.php">Admin Panel</a>
            <a href="manage_gallery.php">Manage Gallery</a>
            <a href="manage_home.php">Manage Home Page</a>
            <a href="create_portfolio.php">Create Portfolio</a>
            <a href="view_messages.php">View Messages</a>
            <?php if ($unreadCount > 0): ?>
                <span class="notification">(<?php echo $unreadCount; ?> new)</span>
            <?php endif; ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </header>

    <section id="portfolio-form">
        <h2><?php echo $editMode ? 'Edit Portfolio' : 'Create Portfolio'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="portfolio_id" value="<?php echo $editMode ? htmlspecialchars($portfolioData['id']) : ''; ?>">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo $editMode ? htmlspecialchars($portfolioData['title']) : ''; ?>" placeholder="Portfolio Title" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Portfolio Description" required><?php echo $editMode ? htmlspecialchars($portfolioData['description']) : ''; ?></textarea>

            <label for="experience">Experience</label>
            <textarea id="experience" name="experience" placeholder="Describe your photography experience" required><?php echo $editMode ? htmlspecialchars($portfolioData['experience']) : ''; ?></textarea>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <option value="Photography" <?php echo $editMode && $portfolioData['category'] === 'Photography' ? 'selected' : ''; ?>>Photography</option>
                <option value="Design" <?php echo $editMode && $portfolioData['category'] === 'Design' ? 'selected' : ''; ?>>Design</option>
                <option value="Artwork" <?php echo $editMode && $portfolioData['category'] === 'Artwork' ? 'selected' : ''; ?>>Artwork</option>
                <option value="Portrait" <?php echo $editMode && $portfolioData['category'] === 'Portrait' ? 'selected' : ''; ?>>Portrait</option>
                <option value="Landscape" <?php echo $editMode && $portfolioData['category'] === 'Landscape' ? 'selected' : ''; ?>>Landscape</option>
                <option value="Event" <?php echo $editMode && $portfolioData['category'] === 'Event' ? 'selected' : ''; ?>>Event</option>
                <option value="Wedding" <?php echo $editMode && $portfolioData['category'] === 'Wedding' ? 'selected' : ''; ?>>Wedding</option>
                <option value="Fashion" <?php echo $editMode && $portfolioData['category'] === 'Fashion' ? 'selected' : ''; ?>>Fashion</option>
                <option value="Food" <?php echo $editMode && $portfolioData['category'] === 'Food' ? 'selected' : ''; ?>>Food</option>
                <option value="Sports" <?php echo $editMode && $portfolioData['category'] === 'Sports' ? 'selected' : ''; ?>>Sports</option>
                <option value="Travel" <?php echo $editMode && $portfolioData['category'] === 'Travel' ? 'selected' : ''; ?>>Travel</option>
                <option value="Commercial" <?php echo $editMode && $portfolioData['category'] === 'Commercial' ? 'selected' : ''; ?>>Commercial</option>
                <option value="Nature" <?php echo $editMode && $portfolioData['category'] === 'Nature' ? 'selected' : ''; ?>>Nature</option>
                <option value="Underwater" <?php echo $editMode && $portfolioData['category'] === 'Underwater' ? 'selected' : ''; ?>>Underwater</option>
                <option value="Architectural" <?php echo $editMode && $portfolioData['category'] === 'Architectural' ? 'selected' : ''; ?>>Architectural</option>
                <option value="Astrophotography" <?php echo $editMode && $portfolioData['category'] === 'Astrophotography' ? 'selected' : ''; ?>>Astrophotography</option>
                <option value="Street" <?php echo $editMode && $portfolioData['category'] === 'Street' ? 'selected' : ''; ?>>Street</option>
            </select>

            <label for="portfolio_images">Upload Images</label>
            <input type="file" id="portfolio_images" name="portfolio_images[]" multiple accept="image/*" required>

            <input type="submit" value="<?php echo $editMode ? 'Update Portfolio' : 'Create Portfolio'; ?>">
        </form>
        <p><?php echo $portfolioMessage; ?></p>
    </section>

    <section id="portfolio-list">
        <h2>Your Portfolios</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Experience</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($portfolios as $portfolio): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($portfolio['image_path']); ?>" alt="Portfolio Image"></td>
                        <td><?php echo htmlspecialchars($portfolio['title']); ?></td>
                        <td><?php echo htmlspecialchars($portfolio['description']); ?></td>
                        <td><?php echo htmlspecialchars($portfolio['experience']); ?></td>
                        <td><?php echo htmlspecialchars($portfolio['category']); ?></td>
                        <td>
                            <a href="?edit_id=<?php echo $portfolio['id']; ?>">Edit</a>
                            <a href="?delete_id=<?php echo $portfolio['id']; ?>" onclick="return confirm('Are you sure you want to delete this portfolio?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
