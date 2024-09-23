<?php
session_start();
include('../LogReg/database.php');

// Fetch available products
$inventory_sql = "SELECT id, name, price, quantity FROM product WHERE quantity > 0";
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

// After successful payment, update the quantity in the database
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $update_quantity_sql = "UPDATE product SET quantity = quantity - ? WHERE id = ?";
        $stmt = $conn->prepare($update_quantity_sql);
        $stmt->bind_param("ii", $quantity, $product_id);
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
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>