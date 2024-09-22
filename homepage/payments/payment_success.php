// File: payment_success.php
<?php
session_start();
include ('../LogReg/database.php');


$conn->query("UPDATE orders SET status='Paid' WHERE id='$order_id'");

// Assuming that session contains order details, such as order_id
if (isset($_SESSION['order_id'])) {
    $order_id = $_SESSION['order_id'];
    $email = $_SESSION['email'];  // User's email
    $amount = $_SESSION['amount'];  // Total amount paid



    // Clear the cart and session data related to the order
    unset($_SESSION['cart'], $_SESSION['order_id'], $_SESSION['amount']);
} else {
    // If session data is not available, redirect to home or cart
    header('Location: cart.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - EZReborn</title>
    <style>
        .success-container {
            padding: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .success-message {
            color: green;
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>Payment Successful</h1>
        <p class="success-message">Thank you for your payment of ₱<?php echo number_format($amount / 100, 2); ?>.</p>
        <p>Your order (ID: <?php echo $order_id; ?>) has been processed successfully.</p>
        <a href="index.php">Return to Homepage</a>
    </div>
</body>
</html>
