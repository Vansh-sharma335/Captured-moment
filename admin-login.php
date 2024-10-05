<?php
session_start();

// Hardcoded admin credentials
$admin_username = "admin";
$admin_password = "admin123";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate credentials
    if ($username === $admin_username && $password === $admin_password) {
        // Set session for the admin
        $_SESSION['user_id'] = $admin_username;
        header("Location: admin-dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <h1>Admin Login</h1>
    </header>

    <div class="wrapper">
        <div class="form-container">
            <div class="form-box">
                <h2>Login</h2>
                <?php if (isset($error)) { echo '<div class="error">' . $error . '</div>'; } ?>
                <form method="POST" action="">
                    <div class="input-box">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Your Website. All Rights Reserved.</p>
    </footer>
</body>
</html>
