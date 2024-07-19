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


$query = "SELECT p.*, s.name AS seller_name
           FROM products p
           JOIN seller s ON p.seller_id = s.id
           ORDER BY RAND()
           LIMIT 1";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$product = mysqli_fetch_assoc($result);

t
mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        .product {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }
        .product img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="product">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
        <p>Seller: <?php echo htmlspecialchars($product['seller_name']); ?></p>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <img src="seller/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <form action="cart.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="action" value="add">
            <button type="submit">Add to Cart</button>
        </form>
        <button onclick="location.reload()">Show Another Random Product</button>
    </div>
</body>
</html>