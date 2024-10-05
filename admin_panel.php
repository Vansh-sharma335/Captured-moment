<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$portfolioMessage = "";
$unreadCount = 0; // Initialize unreadCount

// Database connection details
$host = 'localhost'; // Adjust if needed
$dbname = 'captured_moments'; // Your database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

try {
    // Establishing a database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetching the count of unread messages
    $stmtUnreadCount = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0");
    $unreadCount = $stmtUnreadCount->fetchColumn();

    // Handle portfolio creation logic
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Assuming you handle the file upload and database insertion logic here
        $title = $_POST['title'];
        $description = $_POST['description'];
        
        // Handle file upload
        if (isset($_FILES['portfolio_image']) && $_FILES['portfolio_image']['error'] == UPLOAD_ERR_OK) {
            $imageTempPath = $_FILES['portfolio_image']['tmp_name'];
            $imageName = $_FILES['portfolio_image']['name'];
            $imagePath = "uploads/" . basename($imageName); // Define your uploads directory

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($imageTempPath, $imagePath)) {
                // Database insertion logic goes here
                // For example: Insert into portfolio table
                $stmtInsert = $pdo->prepare("INSERT INTO portfolios (title, description, image_path) VALUES (:title, :description, :image_path)");
                $stmtInsert->bindParam(':title', $title);
                $stmtInsert->bindParam(':description', $description);
                $stmtInsert->bindParam(':image_path', $imagePath);
                $stmtInsert->execute();

                $portfolioMessage = "Portfolio created successfully!";
            } else {
                $portfolioMessage = "Error uploading the image.";
            }
        } else {
            $portfolioMessage = "Error: " . $_FILES['portfolio_image']['error'];
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        </form>
        <p id="portfolio-message"><?php echo htmlspecialchars($portfolioMessage); ?></p>
    </section>

    <footer>
        <p>&copy; 2024 Captured Moments</p>
    </footer>
</body>
</html>
