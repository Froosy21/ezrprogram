<?php
require '../vendor/autoload.php'; // Guzzle for API requests
include('../LogReg/database.php'); // Include your database connection

use GuzzleHttp\Client;

function fetchAllPayments() {
    $secretKey = 'sk_test_Rq5WQbJcwvAnu4ewEgurmSfz'; // PayMongo secret key
    $client = new Client(['base_uri' => 'https://api.paymongo.com/']);

    try {
        $response = $client->request('GET', 'v1/payments', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                'Accept' => 'application/json',
            ]
        ]);

        return json_decode($response->getBody(), true)['data']; // Return payment data
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage()); // Exit on failure
    }
}

function updateOrderStatuses($conn, $payments) {
    foreach ($payments as $payment) {
        $email = $payment['attributes']['billing']['email'] ?? null;
        $status = $payment['attributes']['status'];

        if ($email && $status === 'paid') {
            // Update order status in the database for matching email
            $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE email = ? AND status != 'paid'");
            $stmt->bind_param('s', $email);
            $stmt->execute();
        }
    }
}

// Fetch payments from PayMongo and update the database
$payments = fetchAllPayments();
updateOrderStatuses($conn, $payments);
$conn->close();

// Render the updated orders table
include 'renderOrders.php';
?>
