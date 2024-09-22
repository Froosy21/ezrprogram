<?php
session_start();
include ('../LogReg/database.php');

$sql = "SELECT * FROM orders ORDER BY email, order_date";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dateKey = date('Y-m-d', strtotime($row['order_date']));
        $orders[$row['email']][$dateKey][] = $row;
    }
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
                        <th>Email</th>
                        <th>Date</th>
                        <th>Orders</th>
                        <th>Total Purchase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $email => $userOrdersByDate) : ?>
                        <?php foreach ($userOrdersByDate as $date => $userOrders) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($email); ?></td>
                                <td><?php echo htmlspecialchars($date); ?></td>
                                <td>
                                    <ul>
                                        <?php 
                                        $totalPurchase = 0;
                                        foreach ($userOrders as $order) : 
                                            $totalPurchase += $order['price'] * $order['quantity'];
                                        ?>
                                            <li>
                                                <?php echo htmlspecialchars($order['product_name']); ?> 
                                                (<?php echo htmlspecialchars($order['quantity']); ?>) - 
                                                ₱<?php echo number_format($order['price'], 2); ?> 
                                                (<?php echo htmlspecialchars($order['order_date']); ?>)
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
