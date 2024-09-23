<?php
session_start();
include ('../LogReg/database.php');

$sql = "SELECT * FROM orders ORDER BY order_date DESC, email";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dateKey = date('Y-m-d', strtotime($row['order_date']));
        $emailKey = $row['email'];
        if (!isset($orders[$dateKey][$emailKey])) {
            $orders[$dateKey][$emailKey] = [];
        }
        $orders[$dateKey][$emailKey][] = $row;
    }
    
    // Sort the outer array by key (date) in descending order
    krsort($orders);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - EZReborn</title>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
    <nav class="content">
        <h1>Orders</h1>

        <?php if (empty($orders)) : ?>
            <p>No orders found.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Total Purchase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $date => $emailOrders) : ?>
                        <?php foreach ($emailOrders as $email => $ordersByDateAndEmail) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($date); ?></td>
                                <td><?php echo htmlspecialchars($email); ?></td>
                                <td>
                                    <ul>
                                        <?php 
                                        $totalPurchase = 0;
                                        foreach ($ordersByDateAndEmail as $order) : 
                                            $totalPurchase += $order['price'] * $order['quantity'];
                                        ?>
                                            <li>
                                                <?php echo htmlspecialchars($order['product_name']); ?> 
                                                (<?php echo htmlspecialchars($order['quantity']); ?>) - 
                                                ₱<?php echo number_format($order['price'], 2); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>₱<?php echo number_format($totalPurchase, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </nav>
</body>
</html>
