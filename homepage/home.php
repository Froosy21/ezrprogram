<?php  
session_start();
include ('../LogReg/database.php');

// Fetch featured products from the database
$sql = "SELECT id, name, price, imagePath FROM product LIMIT 4"; // Limit to 4 featured products
$result = $conn->query($sql);

$featured_products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featured_products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => '../admin/' . $row['imagePath'],
        ];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZReborn Home Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Example styles for slider */
        .slider {
            width: 100%;
            overflow: hidden;
        }
        .slides {
            display: flex;
            transition: transform 0.5s ease;
        }
        .slide {
            min-width: 100%;
            flex: 0 0 auto;
        }

        .featured-products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px 0;
        }

        .product-card {
            border: 1px solid #ddd;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin: 10px;
            text-align: center;
            width: calc(25% - 20px); /* 4 products in a row */
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }

        .product-card h3 {
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .product-card p {
            font-size: 1rem;
            color: #555;
        }
        /* Navbar Styles */
        nav {
            display: flex;
            justify-content: center; /* Center the navigation items */
            background-color: #c21212; /* Match the header background */
            padding: 10px 0; /* Vertical padding */
            }

        nav ul {
            list-style: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            display: flex; /* Display items in a row */
            }

        nav ul li {
            padding: 15px 20px; /* Space between items */
            }

        nav ul li a {
            color: white; /* Link color */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Make links bold */
            transition: color 0.3s; /* Smooth color transition */
            }

        nav ul li a:hover {
            color: #ff5733; /* Change color on hover */
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

/* Navbar Styles */
        nav {
            display: flex;
            justify-content: center; /* Center the navigation items */
            background-color: #c21212; /* Match the header background */
            padding: 10px 0; /* Vertical padding */
            }

        nav ul {
            list-style: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            display: flex; /* Display items in a row */
            }

        nav ul li {
            padding: 15px 20px; /* Space between items */
        }

        nav ul li a {
            color: white; /* Link color */
            text-decoration: none; /* Remove underline */
            font-weight: bold; /* Make links bold */
            transition: color 0.3s; /* Smooth color transition */
        }

        nav ul li a:hover {
            color: #ff5733; /* Change color on hover */
        }

        /* About Section */
        .about {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
            background-color: #fff;
        }

        .about-content {
            max-width: 600px;
            margin-right: 20px;
        }

        .about h1 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .about p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .about-image img {
            max-width: 400px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Social Media Section */
        .social-media {
            text-align: center;
            padding: 50px 0;
            background-color: #222;
            color: white;
        }

        .social-media h2 {
            margin-bottom: 20px;
        }

        .social-media a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .social-media a:hover {
            color: #ff5733;
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
        <section class="welcome">
            <h2>Featured Products</h2>
        </section>

        <section class="featured-products">
            <?php if (!empty($featured_products)): ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="product-card">
                        <a href="shop.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p>₱<?php echo number_format($product['price'], 2); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No featured products available.</p>
            <?php endif; ?>
        </section>

        <section class="news">
            <h2>News</h2>
            <div class="slider">
                <div class="slides">
                    <div class="slide">
                        <h3>Latest events</h3>
                        <p>Visit our events page for more info.</p>
                    </div>
                    <div class="slide">
                        <h3>New Gears Available</h3>
                        <p>Visit our shop page</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const slides = document.querySelector('.slides');
        let slideIndex = 0;

        function showSlides() {
            const slidesWidth = slides.offsetWidth;
            slideIndex++;
            if (slideIndex >= slides.children.length) {
                slideIndex = 0;
            }
            slides.style.transform = `translateX(${-slideIndex * slidesWidth}px)`;
            setTimeout(showSlides, 3000); // Change slide every 3 seconds
        }

        showSlides();

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
