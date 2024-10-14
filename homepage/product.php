<?php
session_start();
include('../LogReg/database.php');

$product_id = (int)$_GET['id'];

$sql = "SELECT id, name, price, quantity, imagePath, description FROM product WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    header('Location: shop.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = (int)$_POST['quantity'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header('Location: product.php?id=' . $product_id);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && $quantity <= $product['quantity']) {
        $sql = "UPDATE product SET quantity = quantity - $quantity WHERE id = $product_id";
        $conn->query($sql);
    }
    header('Location: product.php?id=' . $product_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - EZReborn</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-detail {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
        }
        .product-image {
            width: 40%;
            margin: 10px;
        }
        .product-info {
            width: 60%;
            margin: 10px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-description {
            margin-top: 20px;
        }

        .product-description h3 {
            margin-bottom: 10px;
        }

        .product-description p {
            line-height: 1.6;
            text-align: justify;
        }
        .product-image img {
            width: 100%;
            height: auto;
        }
        .product-info h2, .product-info p {
            text-align: left;
        }
        /* Hamburger Menu */
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
                <li><a href="shop.php">Shop</a></li>
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
        
        <div class="product-detail">
            <div class="product-image">
                <img src="../admin/<?php echo htmlspecialchars($product['imagePath']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p>₱<?php echo number_format($product['price'], 2); ?></p>
                <p>Quantity: <?php echo $product['quantity']; ?></p>
                <form action="product.php?id=<?php echo $product_id; ?>" method="post">
                    <input type="number" name="quantity" min="1" max="<?php echo $product['quantity']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
            <div class="product-description">
                <h3>Description</h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
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