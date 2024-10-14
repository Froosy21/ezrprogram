<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>
<body>
<nav class="content">
    <h1>Add Product</h1>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product-name">Product Name:</label>
            <input type="text" id="product-name" name="product_name" required>
        </div>
        <div class="form-group">
            <label for="product-price">Price:</label>
            <input type="number" id="product-price" name="product_price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="product_quantity">Quantity:</label>
            <input type="number" id="product-quantity" name="product_quantity" step="1" required>
        </div>
        <div class="form-group">
            <label for="product-image">Product Image:</label>
            <input type="file" id="product-image" name="product_image" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="product-description">Description:</label>
            <input type="number" id="product-description" name="product_description" required>
        </div>
        <div class="form-group">
            <label for="product-weight">Weight:</label>
            <input type="number" id="product-weight" name="product_weight" required>
        </div>
        <div class="form-group">
            <button type="submit">Add Product</button>
        </div>
    </form>
</nav>
</body>
</html>
