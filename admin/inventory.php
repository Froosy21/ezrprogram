<?php
session_start();
include('../LogReg/database.php');

// Fetch available products from the 'inventory' table
$inventory_sql = "SELECT id, name, price, in_stock, out_stock, status FROM inventory";
$result = $conn->query($inventory_sql);

$products = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        $message = "No products found.";
    }
} else {
    $message = "Error fetching products: " . $conn->error;
}

// After payment, update in_stock and out_stock in the inventory table
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity_purchased) {
        // Fetch the current stock for this product
        $check_quantity_sql = "SELECT in_stock, out_stock FROM inventory WHERE id = ?";
        $stmt = $conn->prepare($check_quantity_sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($current_stock, $current_out_stock);
        $stmt->fetch();
        $stmt->close();

        // Calculate new in_stock and out_stock values
        $new_in_stock = max(0, $current_stock - $quantity_purchased);
        $new_out_stock = $current_out_stock + $quantity_purchased;

        // Determine the new status
        $new_status = ($new_in_stock == 0) ? 'Sold Out' : 'In Stock';

        // Update the inventory table
        $update_inventory_sql = "UPDATE inventory SET in_stock = ?, out_stock = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_inventory_sql);
        $stmt->bind_param("iisi", $new_in_stock, $new_out_stock, $new_status, $product_id);
        $stmt->execute();
        $stmt->close();
    }
    unset($_SESSION['cart']);  // Clear the cart after updating inventory
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="content">
        <h1>Inventory</h1>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>In</th>
                    <th>Out </th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['in_stock']); ?></td>
                        <td><?php echo htmlspecialchars($product['out_stock']); ?></td>
                        <td><?php echo htmlspecialchars($product['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
