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

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Please log in as an admin.");
}

// Fetch all messages
$query = "SELECT m.*, 
          COALESCE(b.first_name, s.name) AS sender_name,
          COALESCE(b2.first_name, s2.name) AS receiver_name
          FROM messages m
          LEFT JOIN buyer b ON m.sender_email = b.email
          LEFT JOIN seller s ON m.sender_email = s.email
          LEFT JOIN buyer b2 ON m.receiver_email = b2.email
          LEFT JOIN seller s2 ON m.receiver_email = s2.email
          ORDER BY m.timestamp DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Pagination
$messages_per_page = 20;
$total_messages = mysqli_num_rows($result);
$total_pages = ceil($total_messages / $messages_per_page);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max(1, min($page, $total_pages));
$offset = ($page - 1) * $messages_per_page;

$query .= " LIMIT $offset, $messages_per_page";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View All Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            padding: 5px 10px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin-right: 5px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Admin - View All Messages</h1>

    <table>
        <thead>
            <tr>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Message</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['sender_name'] . ' (' . $row['sender_email'] . ')'); ?></td>
                    <td><?php echo htmlspecialchars($row['receiver_name'] . ' (' . $row['receiver_email'] . ')'); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <a href="?page=<?php echo $i; ?>" <?php echo ($i == $page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <?php
    // Free result set
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>
</body>
</html>