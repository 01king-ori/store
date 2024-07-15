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
if (!isset($_SESSION['seller_id'])) {
    die("Please log in as a seller to send messages.");
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

// Fetch all buyers for the dropdown
$buyer_query = "SELECT id, email, first_name, surname FROM buyer";
$buyer_result = mysqli_query($conn, $buyer_query);
$buyers = mysqli_fetch_all($buyer_result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_email = $_POST['receiver_email'];
    $message = $_POST['message'];

    if (empty($receiver_email) || empty($message)) {
        $error = "Both fields are required.";
    } else {
        $query = "INSERT INTO messages (sender_email, receiver_email, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "sss", $seller_email, $receiver_email, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Message sent successfully!";
        } else {
            $error = "Error sending message: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to Buyer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        h1 { color: #333; }
        form { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        select, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Send Message to Buyer</h1>
    
    <?php
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    if (isset($success)) {
        echo "<p class='success'>$success</p>";
    }
    ?>

    <form method="POST">
        <label for="receiver_email">Select Buyer:</label>
        <select id="receiver_email" name="receiver_email" required>
            <option value="">Choose a buyer</option>
            <?php foreach ($buyers as $buyer): ?>
                <option value="<?php echo htmlspecialchars($buyer['email']); ?>">
                    <?php echo htmlspecialchars($buyer['first_name'] . ' ' . $buyer['surname'] . ' (' . $buyer['email'] . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>

        <input type="submit" value="Send Message">
    </form>

    <a href="seller_messages.php">Back to Messages</a>
</body>
</html>