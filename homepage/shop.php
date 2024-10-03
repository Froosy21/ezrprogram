<?php
session_start();
include ('../LogReg/database.php');

$sql = "SELECT id, name, price, quantity, imagePath, discount FROM product";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'stock' => $row['quantity'],  
            'image' => '../admin/' . $row['imagePath'],
            'discount' => $row['discount']
        ];
    }
}

$search_query = isset($_GET['search']) ? strtolower($_GET['search']) : '';

$filtered_products = array_filter($products, function($product) use ($search_query) {
    return empty($search_query) || strpos(strtolower($product['name']), $search_query) !== false;
});

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['email'])) {
        header('Location: ../LogReg/login.php'); // Redirect unregistered users to login page
        exit();
    }

    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Fetch current stock from the database
    $stmt = $conn->prepare("SELECT quantity FROM product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($quantity > $stock) {
        $message = "Error: Not enough stock available.";
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }

        $new_stock = max(0, $stock - $quantity);
        $stmt = $conn->prepare("UPDATE product SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_stock, $product_id);
        $stmt->execute();
        $stmt->close();

        header('Location: shop.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - EZReborn</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Main Styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #c21212;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        header img.logo {
            width: 80px;
            height: auto;
            vertical-align: middle;
        }

        header h1 {
            display: inline;
            margin: 0;
            padding: 0;
            vertical-align: middle;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            padding: 15px 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        nav ul li a:hover {
            color: #c21212;
        }

        /* Hamburger Menu */
        .hamburger-menu {
            position: relative;
            display: inline-block;
        }

        .hamburger-icon {
            font-size: 24px;
            cursor: pointer;
            color: white;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .dropdown-menu ul li {
            padding: 10px 20px;
        }

        .dropdown-menu ul li a {
            text-decoration: none;
            color: black;
            display: block;
        }

        .dropdown-menu ul li:hover {
            background-color: #f1f1f1;
        }

        /* Show dropdown when active */
        .hamburger-menu.active .dropdown-menu {
            display: block;
        }

        /* Shop Styles */
        .shop-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            background-color: white;
            transition: transform 0.3s ease;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-card h3 {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .product-card p {
            font-size: 1rem;
            color: #555;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }

        .sold-out {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <img src="img/EzR Logo.png" alt="Logo" class="logo">
        <h1>EZ reborn gears</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li class="current"><a href="shop.php">Shop</a></li>
                <li><a href="calendar.php">Events</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
        <!-- Hamburger menu -->
        <div class="hamburger-menu">
            <div class="hamburger-icon">&#9776;</div>
            <div class="dropdown-menu">
                <ul>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="user_orders.php">Your Orders</a></li>
                    <li><a href="../LogReg/logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        <div class="shop-container">
            <!-- Search bar -->
            <form class="search-bar" action="shop.php" method="get">
                <input type="text" name="search" placeholder="Search for products..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>

            <section class="product-grid">
                <?php if (empty($filtered_products)) : ?>
                    <p>No products found.</p>
                <?php else : ?>
                    <?php foreach ($filtered_products as $product_id => $product) : ?>
                        <div class="product-card">
                            <a href="product.php?id=<?php echo $product_id; ?>">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            </a>

                            <!-- Display stock status -->
                            <?php if ($product['stock'] == 0): ?>
                                <p class="sold-out">SOLD OUT</p>
                            <?php else: ?>
                                <p>In Stock: <?php echo $product['stock']; ?></p>
                                <p>₱<?php echo number_format($product['price'], 2); ?></p>
                            <?php endif; ?>

                            <!-- Add to Cart form or Login prompt -->
                            <form action="shop.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <?php if ($product['stock'] > 0): ?>
                                    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                                    <?php if (isset($_SESSION['email'])): ?>
                                        <button type="submit" name="add_to_cart">Add to Cart</button>
                                    <?php else: ?>
                                        <p><a href="../LogReg/login.php">Login to add to cart</a></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" disabled>Sold Out</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <script>
        const hamburgerMenu = document.querySelector('.hamburger-menu');
        const hamburgerIcon = document.querySelector('.hamburger-icon');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        hamburgerIcon.addEventListener('click', () => {
            hamburgerMenu.classList.toggle('active');
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
