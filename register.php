<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'online_store';

$conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST["submit"])) {
    $first_name = htmlspecialchars($_POST['first_name']);
    $surname = htmlspecialchars($_POST['surname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['number']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    if (!preg_match('/@students\.tukenya\.ac\.ke$/', $email)) {
        echo "Registration Failed: This is only accessible to TUK students.";
    } elseif ($password === $confirmPassword) {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert into buyer table
            $buyer_query = "INSERT INTO buyer (first_name, surname, username, email, phone_number, password) VALUES (?, ?, ?, ?, ?, ?)";
            $buyer_stmt = mysqli_prepare($conn, $buyer_query);
            mysqli_stmt_bind_param($buyer_stmt, "ssssss", $first_name, $surname, $username, $email, $phone_number, $password);
            mysqli_stmt_execute($buyer_stmt);
            mysqli_stmt_close($buyer_stmt);

            // Insert into seller table
            $seller_query = "INSERT INTO seller (name, email, phone_number, password) VALUES (?, ?, ?, ?)";
            $seller_stmt = mysqli_prepare($conn, $seller_query);
            $name = $first_name . ' ' . $surname;
            mysqli_stmt_bind_param($seller_stmt, "ssss", $name, $email, $phone_number, $password);
            mysqli_stmt_execute($seller_stmt);
            mysqli_stmt_close($seller_stmt);

            // If we got here, it means both insertions were successful
            mysqli_commit($conn);
            echo "Registration successful! You are now registered as both a buyer and a seller.";
            header("Location: dashboard.php");
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Registration failed: " . $e->getMessage();
        }
    } else {
        echo "Registration Failed: Passwords do not match.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="css/reg.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
        <h2>User Registration</h2>
        <form action="" method="post" autocomplete="off">
            <label for="first_name">First name:</label>
            <input type="text" name="first_name" id="first_name" placeholder="Enter first name" required>
            
            <label for="surname">Surname:</label>
            <input type="text" name="surname" id="surname" placeholder="Enter surname" required>
            
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter username" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter email" required>
            
            <label for="number">Phone Number:</label>
            <input type="tel" name="number" id="phone_number" placeholder="Enter phone number" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter password" required>
            
            <label for="confirmpassword">Confirm Password:</label>
            <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm password" required>
            
            
    <button type="submit" name="submit">Register</button>
</form>
        <a href="login.php">Already have an account? Login</a>
    </div>
</body>
</html>
