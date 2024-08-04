<?php
// M-Pesa will send a POST request to this endpoint with the payment result
$data = file_get_contents('php://input');
$transaction = json_decode($data, true);

// Log the transaction or handle it as needed
file_put_contents('mpesa_callback_log.txt', print_r($transaction, true));

// Extract necessary details and update the order status in your database
$conn = new mysqli("localhost", "root", "", "laundry");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderId = $transaction['Body']['stkCallback']['CheckoutRequestID'];
$resultCode = $transaction['Body']['stkCallback']['ResultCode'];

if ($resultCode == 0) {
    // Payment was successful
    $status = 'Paid';
} else {
    // Payment failed
    $status = 'Failed';
}

$updateOrderQuery = "UPDATE orders SET status = ? WHERE checkout_request_id = ?";
$stmt = $conn->prepare($updateOrderQuery);
$stmt->bind_param("ss", $status, $orderId);
$stmt->execute();
$stmt->close();

$conn->close();
?>
