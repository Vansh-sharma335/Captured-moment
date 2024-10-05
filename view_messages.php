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

    // Initialize the unread count
    $unreadCount = 0;

    // Delete message logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $messageId = $_POST['message_id'];
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->bindParam(':id', $messageId, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
        exit;
    }

    // Fetching unread messages
    $stmtUnread = $pdo->query("SELECT * FROM messages WHERE is_read = 0 ORDER BY submitted_at DESC");
    $unreadMessages = $stmtUnread->fetchAll(PDO::FETCH_ASSOC);

    // Count unread messages
    $unreadCount = count($unreadMessages); // Set unread count here

    // Fetching old messages
    $stmtOld = $pdo->query("SELECT * FROM messages WHERE is_read = 1 ORDER BY submitted_at DESC");
    $oldMessages = $stmtOld->fetchAll(PDO::FETCH_ASSOC);

    // Mark all unread messages as read
    $pdo->query("UPDATE messages SET is_read = 1 WHERE is_read = 0");

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Messages - Captured Moments</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Keeping the existing styles intact */
        .message-box {
            margin-bottom: 20px;
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .message-box h3 {
            margin-bottom: 10px;
            font-size: 1.4em;
        }
        .message-info {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
        }
        .message-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .message-details p {
            margin: 0;
        }
        .delete-button, .reply-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .reply-button {
            background-color: #2ecc71; /* Different color for reply */
        }
        .notification {
            color: red;
            font-weight: bold;
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

<section id="messages">
    <h2>Unread Messages</h2>
    <?php if (empty($unreadMessages)): ?>
        <p>No unread messages.</p>
    <?php else: ?>
        <?php foreach ($unreadMessages as $message): ?>
            <div class="message-box">
                <h3><?php echo htmlspecialchars($message['subject']); ?></h3>
                <div class="message-info">
                    <div class="message-details">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($message['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        <p><strong>Submitted At:</strong> <?php echo htmlspecialchars($message['submitted_at']); ?></p>
                    </div>
                    <form method="POST" action="">
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                        <button type="submit" name="delete" class="delete-button">Delete</button>
                    </form>
                </div>
                <!-- Ensure the reply button redirects to the sender's email -->
                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Reply to: <?php echo urlencode($message['subject']); ?>" class="reply-button">Reply</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2>Old Messages</h2>
    <?php if (empty($oldMessages)): ?>
        <p>No old messages.</p>
    <?php else: ?>
        <?php foreach ($oldMessages as $message): ?>
            <div class="message-box">
                <h3><?php echo htmlspecialchars($message['subject']); ?></h3>
                <div class="message-info">
                    <div class="message-details">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($message['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        <p><strong>Submitted At:</strong> <?php echo htmlspecialchars($message['submitted_at']); ?></p>
                    </div>
                    <form method="POST" action="">
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                        <button type="submit" name="delete" class="delete-button">Delete</button>
                    </form>
                </div>
                <!-- Ensure the reply button redirects to the sender's email -->
                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Reply to: <?php echo urlencode($message['subject']); ?>" class="reply-button">Reply</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<footer>
    <p>&copy; 2024 Captured Moments</p>
</footer>
</body>
</html>
