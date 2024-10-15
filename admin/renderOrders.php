<?php
include('../LogReg/database.php'); // Include your database connection

// Query to fetch all orders, ordered by date and email
$sql = "SELECT * FROM orders ORDER BY order_date DESC, email";
$result = $conn->query($sql);

// Organize orders by date and email
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
}
$conn->close();
?>

<?php if (empty($orders)) : ?>
    <p>No orders found.</p>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Email</th>
                <th>Orders</th>
                <th>Billing Address</th>
                <th>Phone Number</th>
                <th>Total Purchase</th>
                <th>Status</th>
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
                                        (<?php echo htmlspecialchars($order['quantity']); ?>) 
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?php echo htmlspecialchars($ordersByDateAndEmail[0]['address']); ?></td>
                        <td><?php echo htmlspecialchars($ordersByDateAndEmail[0]['phonenum']); ?></td>
                        <td>₱<?php echo number_format($totalPurchase, 2); ?></td>
                        <td><?php echo htmlspecialchars($ordersByDateAndEmail[0]['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
