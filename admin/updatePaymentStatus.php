<?php
require '../vendor/autoload.php'; // Guzzle for API requests
include('../LogReg/database.php'); // Include your database connection

use GuzzleHttp\Client;

// Function to fetch all payments from PayMongo
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

        // Decode and return the data from PayMongo
        $data = json_decode($response->getBody(), true)['data'];
        error_log(print_r($data, true)); // Log the payment data for debugging
        return $data;

    } catch (Exception $e) {
        die('Error: ' . $e->getMessage()); // Exit on failure
    }
}

// Function to update order statuses in the database
function updateOrderStatuses($conn, $payments) {
    foreach ($payments as $payment) {
        // Get the email and status from PayMongo data
        $email = strtolower(trim($payment['attributes']['billing']['email'] ?? null));
        $status = $payment['attributes']['status'];

        // Debug log for email and status
        error_log("Processing payment for email: $email with status: $status");

        // If the status is 'paid', update the order status in the database
        if ($email && $status === 'paid') {
            $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE email = ? AND status != 'paid'");
            $stmt->bind_param('s', $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                error_log("Order status updated for email: $email");
            } else {
                error_log("No order status updated for email: $email");
            }
        }
    }
}

// Fetch payments from PayMongo
$payments = fetchAllPayments();

// Update order statuses in the database
updateOrderStatuses($conn, $payments);

// Close the database connection
$conn->close();

// Render the updated orders table
include 'renderOrders.php';
