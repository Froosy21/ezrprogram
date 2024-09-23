<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - EZReborn</title>
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
        <section class="about">
            <div class="about-content">
                <h1>About EZReborn E-Sports</h1>
                <p>Welcome to EZReborn E-Sports, your ultimate destination for high-quality jerseys and esports clothing. We are passionate about providing gamers with the best tools and gear to enhance their gaming experience.</p>
                <p>At EZReborn, we understand the importance of performance and reliability in clothes. That's why we carefully select and offer a curated collection of products ranging from gaming peripherals to esports jerseys, ensuring that every gamer finds what they need to succeed.</p>
                <p>Our mission is to support the gaming community by providing top-notch customer service and delivering products that meet the highest standards of quality and innovation.</p>
                <p>Thank you for choosing EZReborn E-Sports. Game on!</p>
        </section>

        <!-- Social Media Section -->
        <section class="social-media">
            <h2>Follow Us on Social Media</h2>
            <a href="https://www.facebook.com/EZRGears" target="_blank">Facebook</a>
            <a href="https://www.instagram.com/ezrebornesportsph/" target="_blank">Instagram</a>
        </section>
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