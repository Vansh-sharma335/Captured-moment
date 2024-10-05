<?php
// Database connection details
$host = 'localhost'; // Adjust if needed
$dbname = 'captured_moments'; // Your database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password

try {
    // Establishing a database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $subject = htmlspecialchars($_POST['subject']);
        $message = htmlspecialchars($_POST['message']);

        // Prepare and execute the SQL statement to insert the message
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
        $stmt->execute(['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message]);

        // Assuming the form processing is successful:
        $contact_success = "Thank you, $name. Your message has been submitted successfully!";
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
    <title>Contact - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/all.min.css"> <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="scrip.js" defer></script>
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
    
    <section id="contact">
        <h2>Contact</h2>
        <form id="contact-form" action="contact.php" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Your Name" required>
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" placeholder="Your Email" required>
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Subject" required>
            <label for="message">Your Message</label>
            <textarea id="message" name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send</button>
        </form>
        <p id="contact-message">
            <?php 
            if (isset($contact_success)) { 
                echo htmlspecialchars($contact_success); 
            } 
            ?>
        </p>
        

        <style>
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

        <!-- Social Media Icons -->
<div class="social-media">
            <h2>Follow Us</h2>
            <a href="https://www.facebook.com" target="_blank" class="fab fa-facebook-f"></a>
            <a href="https://www.instagram.com" target="_blank" class="fab fa-instagram"></a>
            <a href="https://www.twitter.com" target="_blank" class="fab fa-twitter"></a>
            <a href="https://www.linkedin.com" target="_blank" class="fab fa-linkedin-in"></a>
        </div>
    </section>
        </style>
    
    <footer>
        <p>&copy; 2024 Captured Moments</p>
    </footer>
</body>
</html>
