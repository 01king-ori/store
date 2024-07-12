<?php
session_start();
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'online_store';

$conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// require_once 'functions.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
// Fetch all transactions
$query = "SELECT t.*, p.name AS product_name, b.username AS buyer_username, s.phone_number AS seller_phone 
          FROM transactions t 
          JOIN products p ON t.product = p.id 
          JOIN buyer b ON t.buyer_id = b.id
          JOIN seller s ON t.seller_id = s.id
          ORDER BY t.created_at DESC";

$result = $conn->query($query);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

$transactions = $result->fetch_all(MYSQLI_ASSOC);?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Buyer</th>
            <th>Seller</th>
            <th>Product</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?php echo $transaction['id']; ?></td>
                <td><?php echo htmlspecialchars($transaction['buyer_username']); ?></td>
                <td><?php echo htmlspecialchars($transaction['seller_phone']); ?></td>
                <td><?php echo htmlspecialchars($transaction['product_name']); ?></td>
                <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                <td><?php echo ucfirst($transaction['status']); ?></td>
                <td><?php echo date('Y-m-d H:i', strtotime($transaction['created_at'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>