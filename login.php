<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin-login.css">
    <script>
        // JavaScript for handling forgot password
        function forgotPassword() {
            const username = prompt("Enter your admin username:");
            if (username) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'action': 'forgot_password',
                        'username': username
                    })
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
    <style>
        .social-media {
            text-align: center; /* Center the social media icons */
            margin: 20px 0; /* Space around the social media section */
        }

        .social-media a {
            margin: 0 10px; /* Space between icons */
            color: #333; /* Default icon color */
            font-size: 24px; /* Size of the icons */
            text-decoration: none; /* Remove underline from links */
            transition: color 0.3s; /* Smooth transition for color change */
        }

        .social-media a:hover {
            color: #007bff; /* Color on hover */
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
            <a href="login.php">Login</a>
            <?php 
            session_start(); // Start the session at the top of the file

            if (isset($_SESSION['user_id'])): ?>
                <a href="admin_panel.php">Admin Panel</a> <!-- Admin Panel link shown after login -->
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> 
            <?php endif; ?>
        </nav>
    </header>

    <div class="login-container">
        <form method="POST" class="login-form">
            <input type="hidden" name="action" value="login">
            <h2>Admin Login</h2>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
            <button type="button" onclick="forgotPassword()">Forgot Password</button>
        </form>

        <?php
        // Handle login
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
            $inputUsername = $_POST['username'];
            $inputPassword = $_POST['password'];

            // Check credentials
            if ($inputUsername === 'vansh' && $inputPassword === 'admin123') {
                $_SESSION['user_id'] = $inputUsername; // Set session variable
                header('Location: admin_panel.php'); // Redirect to admin panel
                exit();
            } else {
                echo '<p style="color: red;">Invalid username or password.</p>';
            }
        }
        ?>
    </div>

    <!-- Social Media Icons -->
    <div class="social-media">
    <h2>Follow Us</h2>
    <a href="https://www.facebook.com" target="_blank" class="fab fa-facebook"></a>
    <a href="https://www.instagram.com" target="_blank" class="fab fa-instagram"></a>
    <a href="https://www.twitter.com" target="_blank" class="fab fa-twitter"></a>
    <a href="https://www.linkedin.com" target="_blank" class="fab fa-linkedin"></a>
</div>

</body>
</html>
