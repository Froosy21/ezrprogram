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
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header('Location: shop.php');
    exit();
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
        .shop-container {
            display: flex;
            flex-wrap: wrap;
        }
        .product-grid {
            width: 80%;
            display: flex;
            flex-wrap: wrap;
            padding: 10px;
        }
        .product-card {
            width: 30%;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100%;
            height: auto;
        }
        .product-card h3, .product-card p {
            text-align: center;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }

        .hamburger-menu {
            position: relative;
            display: inline-block;
        }

        .hamburger-icon {
            font-size: 24px;
            cursor: pointer;
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
                <li><a href="../LogReg/logout.php">Log Out</a></li>
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
            <section class="product-grid">
                <form class="search-bar" action="shop.php" method="get">
                    <input type="text" name="search" placeholder="Search for products..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit">Search</button>
                </form>
                <?php if (empty($filtered_products)) : ?>
                    <p>No products found.</p>
                <?php else : ?>
                    <?php foreach ($filtered_products as $product_id => $product) : ?>
                        <a href="product.php?id=<?php echo $product_id; ?>">
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p>₱<?php echo number_format($product['price'], 2); ?></p>
                                <form action="shop.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="10">
                                    <button type="submit" name="add_to_cart">Add to Cart</button>
                                </form>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <script>
        // Hamburger menu toggle
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