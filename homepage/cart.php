<?php  
session_start();
include('../LogReg/database.php');

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../LogReg/login.php');
    exit();
}

// Fetch products from the database
$sql = "SELECT id, name, price, quantity, weight, imagePath FROM product";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'weight' => $row['weight'],  // Store product weight
            'image' => '../admin/' . $row['imagePath']
        ];
    }
}

// Initialize cart and calculate total
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$total_weight = 0; // Initialize total weight

foreach ($cart as $product_id => $quantity) {
    $product = $products[$product_id];
    $total += $product['price'] * $quantity;
    $total_weight += $product['weight'] * $quantity; // Sum product weights
}

// J&T Shipping rates based on region
$shipping_rates = [
    'Luzon' => [100, 180, 200, 300, 400, 500], // 0-500g, 500g-1kg, ..., 5kg-6kg
    'Visayas' => [105, 195, 220, 330, 440, 550],
    'Mindanao' => [115, 205, 230, 340, 450, 560]
];

// Function to determine shipping fee based on weight and region
function calculateShipping($weight, $region, $rates) {
    $index = min(ceil($weight / 1000), 6) - 1; // Determine rate index based on weight
    return $rates[$region][$index];
}

// Handle AJAX request for shipping calculation
if (isset($_POST['ajax']) && $_POST['ajax'] == 'calculate_shipping') {
    $selected_region = $_POST['region'];
    $shipping_fee = calculateShipping($total_weight, $selected_region, $shipping_rates);
    $grand_total = $total + $shipping_fee;

    // Store the shipping fee in the session for later use during payment processing
    $_SESSION['shipping_fee'] = $shipping_fee;

    // Return JSON response with updated shipping fee and total
    echo json_encode([
        'shipping_fee' => number_format($shipping_fee, 2),
        'grand_total' => number_format($grand_total, 2)
    ]);
    exit();
}

// Handle form submissions for updating cart, removing items, and payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['ajax'])) {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $product_id => $quantity) {
            $quantity = max(0, (int)$quantity);
            if ($quantity === 0) {
                unset($_SESSION['cart'][$product_id]);
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }
        header('Location: cart.php');
        exit();
    }

    if (isset($_POST['remove_item'])) {
        $product_id = $_POST['remove_item'];
        unset($_SESSION['cart'][$product_id]);
        header('Location: cart.php');
        exit();
    }
    
    if (isset($_POST['pay'])) {
        // Get billing address and phone number from the form
        $billing_address = trim($_POST['billing_address']);
        $phone_number = trim($_POST['phone_number']);
        $email = $_SESSION['email'];

        // Retrieve shipping fee from the session
        $shipping_fee = isset($_SESSION['shipping_fee']) ? $_SESSION['shipping_fee'] : 0;
        $grand_total = $total + $shipping_fee; // Add the shipping fee to the total

        $amount = $grand_total * 100; // Amount in cents for PayMongo

        // Check if the inputs are filled
        if (empty($billing_address) || empty($phone_number)) {
            echo "<script>alert('Billing address and phone number are required!');</script>";
        } else {
            // Initialize cURL for PayMongo API
            $curl = curl_init();
            $paymongo_api_key = 'sk_test_Rq5WQbJcwvAnu4ewEgurmSfz';

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

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $response_data = json_decode($response, true);
                $payment_url = $response_data['data']['attributes']['checkout_url'];
                $_SESSION['payment_url'] = $payment_url;

                // Store order details with billing address and phone number
                foreach ($cart as $product_id => $quantity) {
                    $stmt = $conn->prepare("INSERT INTO orders (email, product_name, quantity, price, address, phonenum, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->bind_param("ssiiss", $email, $products[$product_id]['name'], $quantity, $products[$product_id]['price'], $billing_address, $phone_number);
                    $stmt->execute();
                    $stmt->close();
                }

                header('Location: payment.php');
                exit();
            }
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
        /* Navbar Styles */
        nav {
            display: flex;
            justify-content: center;
            background-color: #c21212;
            padding: 10px 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        nav ul li {
            padding: 15px 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ff5733;
        }

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
                    <span>Price: ₱<?php echo number_format($products[$product_id]['price'], 2); ?></span>
                    <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="0" style="width: 60px;">
                    <button type="submit" name="remove_item" value="<?php echo $product_id; ?>">Remove</button>
                </div>
            <?php endforeach; ?>

            <h3>Shipping Region</h3>
            <select name="region" id="region" required>
                <option value="">Select Region</option>
                <option value="Luzon">Luzon</option>
                <option value="Visayas">Visayas</option>
                <option value="Mindanao">Mindanao</option>
            </select>

            <div class="cart-total">
                Total: ₱<?php echo number_format($total, 2); ?><br>
                Shipping: ₱<span id="shipping_fee">0.00</span><br>
                <strong>Grand Total: ₱<span id="grand_total"><?php echo number_format($total, 2); ?></span></strong>
            </div>

            <h3>Billing Information</h3>
            <label for="billing_address">Billing Address:</label><br>
            <input type="text" id="billing_address" name="billing_address" required style="width: 100%; margin-bottom: 10px;"><br>

            <label for="phone_number">Phone Number:</label><br>
            <input type="text" id="phone_number" name="phone_number" required style="width: 100%; margin-bottom: 10px;"><br>

            <button type="submit" name="update_cart">Update Cart</button>
            <button type="submit" name="pay">Pay</button>
        <?php endif; ?>
    </form>
</main>

<script>
// Dynamically update shipping and total when region is selected
document.getElementById('region').addEventListener('change', function() {
    var region = this.value;

    // Send AJAX request to calculate shipping
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'cart.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            var response = JSON.parse(this.responseText);
            document.getElementById('shipping_fee').textContent = response.shipping_fee;
            document.getElementById('grand_total').textContent = response.grand_total;
        }
    };

    xhr.send('ajax=calculate_shipping&region=' + region);
});
</script>

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
