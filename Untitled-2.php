<?php
// Start the PHP session (if needed in future for admin login/signup)
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project 2 - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styling for the specific portfolio layout */
        .portfolio-layout {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .portfolio-image {
            flex: 1;
            max-width: 50%;
        }

        .portfolio-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .portfolio-details {
            flex: 1;
            padding-left: 30px;
        }

        .portfolio-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .portfolio-description {
            font-size: 1.2rem;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Project 2 - Captured Moments</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="gallery.php">Gallery</a>
            <a href="experience.php">Experience</a>
            <a href="contact.php">Contact</a>
            <a href="admin.php">Signup</a>
        </nav>
    </header>

    <!-- Portfolio layout for Vansh Sharma -->
    <section id="portfolio-layout">
        <div class="portfolio-layout">
            <div class="portfolio-image">
                <img src="/camera.jpg" alt="Vansh Sharma Photography">
            </div>
            <div class="portfolio-details">
                <h2 class="portfolio-title"><?php echo "Vansh Sharma"; ?></h2>
                <p class="portfolio-description">
                    <?php
                    // This could be dynamic data from a database or API in the future
                    echo "Nature photography reveals the serene beauty of the world through captivating mobile shots. Explore for a unique perspective on nature.";
                    ?>
                </p>
            </div>
        </div>
    </section>

    <section id="project-gallery">
        <h2><?php echo "Gallery for Project 2"; ?></h2>
        <div class="project-gallery-grid">
            <?php
            // Array to store gallery images and descriptions (this could also come from a database)
            $gallery_items = [
                ["src" => "/20240408_180103.jpg", "alt" => "Project 2 Image 1", "description" => "A serene landscape captured during sunset, highlighting the golden hues of nature."],
                ["src" => "/20240401_181509.jpg", "alt" => "Project 2 Image 2", "description" => "An early morning shot of the misty forest, showcasing the tranquil atmosphere."],
                ["src" => "/20240409_175436.jpg", "alt" => "Project 2 Image 3", "description" => "Close-up of dew drops on a spider web, reflecting the delicate details of nature."],
                ["src" => "/20240409_182303.jpg", "alt" => "Project 2 Image 4", "description" => "A vibrant field of wildflowers in full bloom, capturing the colors of spring."],
            ];

            // Loop through each gallery item and display it
            foreach ($gallery_items as $item) {
                echo '<div class="project-gallery-item">';
                echo '<img src="' . $item['src'] . '" alt="' . $item['alt'] . '">';
                echo '<p class="gallery-description">' . $item['description'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <section id="social-media">
        <h2><?php echo "Follow Us"; ?></h2>
        <div class="social-media-icons">
            <a href="https://www.instagram.com/wildvisit" target="_blank" class="social-icon">
                <img src="1161953_instagram_icon.png" alt="Instagram">
            </a>
            <a href="https://wa.me/yourphonenumber" target="_blank" class="social-icon">
                <img src="843786_whatsapp_icon.png" alt="WhatsApp">
            </a>
            <a href="https://www.facebook.com/profile.php?id=100019267922822" class="social-icon">
                <img src="/5305154_fb_facebook_facebook logo_icon.png" alt="Facebook">
            </a>
            <a href="mailto:vanshsharma9821@gmail.com" class="social-icon">
                <img src="/67-gmail-128.webp" alt="Email">
            </a>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Captured Moments</p>
    </footer>
</body>
</html>
