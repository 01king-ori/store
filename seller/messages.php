<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'online_store';

$conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
session_start();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (!isset($_SESSION['seller_id'])) {
    die("Please log in as a seller to view messages!.");
}

$seller_id = $_SESSION['seller_id'];

// Fetch seller's email
$seller_query = "SELECT email FROM seller WHERE id = ?";
$seller_stmt = mysqli_prepare($conn, $seller_query);
mysqli_stmt_bind_param($seller_stmt, "i", $seller_id);
mysqli_stmt_execute($seller_stmt);
$seller_result = mysqli_stmt_get_result($seller_stmt);
$seller = mysqli_fetch_assoc($seller_result);
$seller_email = $seller['email'];

// Fetch messages
$query = "SELECT m.*, b.first_name, b.surname 
          FROM messages m 
          LEFT JOIN buyer b ON m.sender_email = b.email OR m.receiver_email = b.email
          WHERE m.sender_email = ? OR m.receiver_email = ? 
          ORDER BY m.timestamp DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $seller_email, $seller_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Messages</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        h1 { color: #333; }
        ul { list-style-type: none; padding: 0; }
        li { background-color: #f9f9f9; margin-bottom: 10px; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Seller Messages</h1>
    <a href="send_messages.php">Send New Message</a>
    <ul>
        <?php foreach ($messages as $message): ?>
            <li>
                <?php if ($message['sender_email'] === $seller_email): ?>
                    <strong>You</strong> to
                    <strong><?php echo htmlspecialchars($message['first_name'] . ' ' . $message['surname']); ?></strong>:
                <?php else: ?>
                    <strong><?php echo htmlspecialchars($message['first_name'] . ' ' . $message['surname']); ?></strong> to
                    <strong>You</strong>:
                <?php endif; ?>
                <?php echo htmlspecialchars($message['message']); ?>
                <small>(<?php echo $message['timestamp']; ?>)</small>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>