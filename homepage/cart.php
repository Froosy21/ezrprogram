<?php
session_start();
include('../LogReg/database.php');

// Fetch products
$sql = "SELECT id, name, price, quantity, imagePath FROM product";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'image' => '../admin/' . $row['imagePath']
        ];
    }
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cart as $product_id => $quantity) {
    $total += $products[$product_id]['price'] * $quantity;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pay'])) {
        $email = $_SESSION['email'];
        $amount = $total * 100;  // Amount in cents for PayMongo

        // Initialize cURL for PayMongo API
        $curl = curl_init();
        $paymongo_api_key = 'sk_test_Rq5WQbJcwvAnu4ewEgurmSfz';

        // Prepare data for PayMongo Link API
        $data = [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'currency' => 'PHP',
                    'description' => 'Order Payment for ' . $email,
                    'statement_descriptor' => 'EZReborn',
                    'redirect' => [
                        'success' => 'home.php',
                        'failed' => 'payments/payment_failed.php'
                    ]
                ]
            ]
        ];

        // Set up cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paymongo.com/v1/links",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic " . base64_encode($paymongo_api_key),
                "Content-Type: application/json"
            ]
        ]);

        // Execute cURL request
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        // Error handling
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response_data = json_decode($response, true);
            $payment_url = $response_data['data']['attributes']['checkout_url'];

            // Store the payment link in the session
            $_SESSION['payment_url'] = $payment_url;

            // Store order details in the orders table
            foreach ($cart as $product_id => $quantity) {
                $stmt = $conn->prepare("INSERT INTO orders (email, product_name, quantity, price, order_date) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("ssii", $email, $products[$product_id]['name'], $quantity, $products[$product_id]['price']);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect to payment page
            header('Location: payment.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - EZReborn</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container {
            padding: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .cart-item img {
            width: 100px;
            height: auto;
        }
        .cart-total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
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

    <main class="cart-container">
        <h2>Your Cart</h2>
        <form method="post" action="cart.php">
            <?php if (empty($cart)) : ?>
                <p>Your cart is empty.</p>
            <?php else : ?>
                <?php foreach ($cart as $product_id => $quantity) : ?>
                    <div class="cart-item">
                        <img src="<?php echo htmlspecialchars($products[$product_id]['image']); ?>" alt="<?php echo htmlspecialchars($products[$product_id]['name']); ?>">
                        <span><?php echo htmlspecialchars($products[$product_id]['name']); ?></span>
                        <span>₱<?php echo number_format($products[$product_id]['price'], 2); ?></span>
                        <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="0">
                    </div>
                <?php endforeach; ?>
                <div class="cart-total">
                    Total: ₱<?php echo number_format($total, 2); ?>
                </div>
                <button type="submit" name="update_cart">Update Cart</button>
            <?php endif; ?>
        </form>

        <?php if (!empty($cart)) : ?>
            <div>
                <form method="post" action="cart.php">
                    <button type="submit" name="pay">Pay Now</button>
                </form>
            </div>
        <?php endif; ?>
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
