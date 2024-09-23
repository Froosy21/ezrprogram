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

        /* Shop Now button styles */
        .shop-now-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff5733;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            cursor: pointer;
            font-size: 1rem;
        }

        .shop-now-btn:hover {
            background-color: #ff451a;
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
            <p>Discover the best products for your gaming needs.</p>
        </section>

        <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="img/product1.jpg" alt="Product 1">
                    <h3>Product 1</h3>
                    <p>Php200.99</p>
                    <!-- Shop Now button -->
                    <a href="shop.php" class="shop-now-btn">Shop Now</a>
                </div>
            </div>
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