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

    // Fetch latest portfolios from the gallery
    $sql = "SELECT * FROM gallery ORDER BY created_at DESC"; // Fetch all records
    $stmt = $pdo->query($sql);
    $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    echo "We're experiencing technical difficulties. Please try again later.";
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captured Moments - Gallery</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/all.min.css"> <!-- Local Font Awesome -->
    <style>
        /* Gallery grid */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 10px; /* Adds space between items */
            margin: 20px 0;
        }

        .portfolio-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .portfolio-item img {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .portfolio-item img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .portfolio-item .portfolio-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            text-align: center;
        }

        /* Social Media Icons */
        .social-media {
            text-align: center;
            margin: 20px 0;
        }

        .social-media a {
            margin: 0 10px;
            color: #333;
            font-size: 24px;
            text-decoration: none;
        }

        .social-media a:hover {
            color: #007bff;
        }
    </style>
</head>
<body>

    <header>
        <h1>Captured Moments</h1>
        <nav>
        <a href="index.php">Home</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact</a>
        <a href="portfolios.php">Portfolios</a>
        <a href="login.php">Login</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> 
        <?php endif; ?>
    </nav>
    </header>

    <section id="gallery">
        <h2>Gallery</h2>
        <div class="portfolio-grid">
            <?php foreach ($gallery as $portfolio): ?>
                <div class="portfolio-item">
                    <img src="<?php echo htmlspecialchars($portfolio['image_path']); ?>" alt="<?php echo htmlspecialchars($portfolio['description']); ?>">
                    <div class="portfolio-text"><?php echo htmlspecialchars($portfolio['description']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="social-media">
    <h2>Follow Us</h2>
    <a href="https://www.facebook.com" target="_blank" class="fab fa-facebook"></a>
    <a href="https://www.instagram.com" target="_blank" class="fab fa-instagram"></a>
    <a href="https://www.twitter.com" target="_blank" class="fab fa-twitter"></a>
    <a href="https://www.linkedin.com" target="_blank" class="fab fa-linkedin"></a>
</div>

    <footer>
        <p>&copy; 2024 Captured Moments</p>
    </footer>

</body>
</html>
