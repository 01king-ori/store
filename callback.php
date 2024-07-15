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

// Read the data from the request
$data = file_get_contents('php://input');
$callbackData = json_decode($data, true);

if (isset($callbackData['Body']['stkCallback'])) {
    $callback = $callbackData['Body']['stkCallback'];

    $transactionId = $callback['CheckoutRequestID'];
    $resultCode = $callback['ResultCode'];
    $resultDesc = $callback['ResultDesc'];

    // Fetch the transaction details from the database using the transaction ID
    $query = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $transactionId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $transaction = mysqli_fetch_assoc($result);

    if ($transaction) {
        if ($resultCode == 0) { // Successful transaction
            $status = 'Completed';
            $amount = $callback['CallbackMetadata']['Item'][0]['Value']; // Payment amount
        } else { // Failed transaction
            $status = 'Failed';
            $amount = 0;
        }

        // Update the transaction status in the database
        $updateQuery = "UPDATE transactions SET status = ?, result_desc = ?, amount = ? WHERE transaction_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "ssis", $status, $resultDesc, $amount, $transactionId);
        mysqli_stmt_execute($updateStmt);

        // Update the session with the status message
        if ($resultCode == 0) {
            $_SESSION['success_message'] = "Payment completed successfully.";
        } else {
            $_SESSION['error_message'] = "Payment failed: " . $resultDesc;
        }
    } else {
        $_SESSION['error_message'] = "Transaction not found.";
    }
} else {
    $_SESSION['error_message'] = "Invalid callback data.";
}

mysqli_close($conn);
?>
