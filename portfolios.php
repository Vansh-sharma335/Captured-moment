<?php
session_start();

// Database connection variables
$host = 'localhost';
$dbname = 'captured_moments';
$username = 'root';  // Replace with your database username
$password = '';      // Replace with your database password

// Initialize variables
$portfolios = [];
$portfolio = null;

// Check if a portfolio ID is provided
if (isset($_GET['id'])) {
    $portfolio_id = $_GET['id'];

    try {
        // Create a new PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the portfolio based on ID
        $stmt = $pdo->prepare("SELECT * FROM portfolios WHERE id = :id");
        $stmt->bindParam(':id', $portfolio_id, PDO::PARAM_INT);
        $stmt->execute();
        $portfolio = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    try {
        // Create a new PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch all portfolios
        $stmt = $pdo->prepare("SELECT * FROM portfolios");
        $stmt->execute();
        $portfolios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Portfolios - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <styl>
    <style>
    /* Portfolio grid */
    .portfolio-grid {
        display: flex;
        flex-wrap: wrap; /* Allows items to wrap onto the next line */
        justify-content: flex-start; /* Align items to the start */
        margin: 20px 0;
    }

    .category-item {
        flex: 0 1 calc(33.33% - 1500px); /* Set width to approximately 33% with space for margins */
        margin: 50px;
        text-align: left
        box-sizing: border-box; /* Ensure padding and borders are included in width */
    }

    .category-item a img {
        width: 400px; /* Fixed width */
        height: 300px; /* Fixed height */
        object-fit: cover; /* Ensures the image covers the dimensions */
        border-radius: 5px; /* Optional: Add rounded corners */
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add transition for smooth effect */
    }

    .category-item a:hover img {
        transform: scale(1.05); /* Scale image on hover */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
    }

    footer {
        text-align: center;
        padding: 10px;
        background-color: #333;
        color: #fff;
    }
      /* Social media icons */
      .social-media {
            margin: 20px 0;
            text-align: center;
        }

        .social-media a {
            margin: 0 10px; /* Space between icons */
            color: #333; /* Default icon color */
            font-size: 24px; /* Size of icons */
            transition: color 0.3s ease; /* Transition for color change */
        }

        .social-media a:hover {
            color: #007bff; /* Change color on hover */
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

    <section id="portfolio-detail">
        <?php if ($portfolio): ?>
            <h2><?php echo htmlspecialchars($portfolio['title']); ?></h2>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($portfolio['category']); ?></p>
            <img src="<?php echo htmlspecialchars($portfolio['image_path']); ?>" alt="<?php echo htmlspecialchars($portfolio['title']); ?>" style="max-width: 500px;">
            <p><strong>Description:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($portfolio['description'])); ?></p>
            <p><strong>Experience:</strong> <?php echo nl2br(htmlspecialchars($portfolio['experience'])); ?></p>
        <?php else: ?>
            <h2>All Portfolios</h2>
            <div class="portfolio-grid">
                <?php if (count($portfolios) > 0): ?>
                    <?php foreach ($portfolios as $portfolio): ?>
                        <div class="category-item">
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($portfolio['category']); ?></p>
                            <a href="?id=<?php echo $portfolio['id']; ?>">
                                <img src="<?php echo htmlspecialchars($portfolio['image_path']); ?>" alt="<?php echo htmlspecialchars($portfolio['title']); ?>" style="max-width: 300px;">
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No portfolios have been created yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
<!-- Social Media Icons -->
<div class="social-media">
    <h2>Follow Us</h2>
    <a href="https://www.facebook.com" target="_blank" class="fab fa-facebook-f"></a>
    <a href="https://www.instagram.com" target="_blank" class="fab fa-instagram"></a>
    <a href="https://www.twitter.com" target="_blank" class="fab fa-twitter"></a>
    <a href="https://www.linkedin.com" target="_blank" class="fab fa-linkedin-in"></a>
</div>

<footer>
    <p>&copy; 2024 Captured Moments</p>
</footer>
</body>
    