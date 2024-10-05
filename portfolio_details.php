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

    if (isset($_GET['id'])) {
        // Fetch the portfolio by ID
        $portfolioId = $_GET['id'];
        $sql = "SELECT * FROM portfolios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $portfolioId]);
        $portfolio = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$portfolio) {
            echo "Portfolio not found.";
            exit();
        }

        // Fetch uploaded images for this portfolio
        $sqlImages = "SELECT * FROM portfolio_images WHERE portfolio_id = :portfolio_id";
        $stmtImages = $pdo->prepare($sqlImages);
        $stmtImages->execute(['portfolio_id' => $portfolioId]);
        $images = $stmtImages->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Invalid request.";
        exit();
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($portfolio['title']); ?></h1>
    </header>

    <section id="portfolio-details">
        <img src="<?php echo htmlspecialchars($portfolio['image_path']); ?>" alt="Portfolio Image">
        <p><?php echo htmlspecialchars($portfolio['description']); ?></p>
    </section>

    <!-- Upload Image Form -->
    <section id="upload-image">
        <h2>Upload New Image</h2>
        <form action="upload_image.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="portfolio_id" value="<?php echo htmlspecialchars($portfolio['id']); ?>">
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" class="btn-primary">Upload Image</button>
        </form>
    </section>

    <!-- Display Uploaded Images -->
    <section id="portfolio-images">
        <h2>Uploaded Images</h2>
        <div class="image-grid">
            <?php foreach ($images as $image): ?>
                <div class="image-item">
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Portfolio Image">
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Captured Moments</p>
    </footer>
</body>
</html>
