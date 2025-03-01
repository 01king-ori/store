<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'online_store';


$conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Please log in .");
}

$user_id = $_SESSION['user_id'];

// Fetch buyer's email
$buyer_query = "SELECT email FROM buyer WHERE id = ?";
$buyer_stmt = mysqli_prepare($conn, $buyer_query);
mysqli_stmt_bind_param($buyer_stmt, "i", $user_id);
mysqli_stmt_execute($buyer_stmt);
$buyer_result = mysqli_stmt_get_result($buyer_stmt);
$buyer = mysqli_fetch_assoc($buyer_result);
$buyer_email = $buyer['email'];

// Fetch messages
$query = "SELECT m.*, s.name as seller_name 
          FROM messages m 
          LEFT JOIN seller s ON m.sender_email = s.email OR m.receiver_email = s.email
          WHERE m.sender_email = ? OR m.receiver_email = ? 
          ORDER BY m.timestamp DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $buyer_email, $buyer_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Messages</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        h1 { color: #333; }
        ul { list-style-type: none; padding: 0; }
        li { background-color: #f9f9f9; margin-bottom: 10px; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Buyer Messages</h1>
    <a href="send_message.php">Send New Message</a>
    <ul>
        <?php foreach ($messages as $message): ?>
            <li>
                <?php if ($message['sender_email'] === $buyer_email): ?>
                    <strong>You</strong> to
                    <strong><?php echo htmlspecialchars($message['seller_name']); ?></strong>:
                <?php else: ?>
                    <strong><?php echo htmlspecialchars($message['seller_name']); ?></strong> to
                    <strong>You</strong>:
                <?php endif; ?>
                <?php echo htmlspecialchars($message['message']); ?>
                <small>(<?php echo $message['timestamp']; ?>)</small>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>