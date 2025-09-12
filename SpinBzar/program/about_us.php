<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | SpinBzar</title>
    <link rel="stylesheet" href="../styles/about.css">
    <link rel="stylesheet" href="../styles/landing_page.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="login_landing_page,php">Home</a></li>
            <li><a href="login_product_listing.php">Shop</a></li>
            <li><a href="about_us.php">About Us</a></li>
        </ul>
    </nav>

    <div class="logo">
        <h1 style="color: #FFF;">SpinBzar</h1>
    </div>

    <nav>
        <ul>
            <li><a href="<?= $is_logged_in ? 'buyer/wishlist.php' : 'login.php' ?>">Wishlist</a></li>
            <li><a href="<?= $is_logged_in ? 'buyer/cart.php' : 'login.php' ?>">Cart</a></li>
            <li><a href="<?= $is_logged_in ? 'user_profile.php' : 'login.php' ?>">Profile</a></li>
        </ul>
    </nav>
</header>
    <div class="about-container">
        <h1>About SpinBzar</h1>
        <p>Welcome to <strong>SpinBzar</strong>, your one-stop destination for a seamless and enjoyable shopping experience. Founded with the vision of supporting local sellers and providing quality products to buyers, SpinBzar connects communities through commerce.</p>

        <h2>Our Mission</h2>
        <p>At SpinBzar, we aim to empower both small businesses and customers by offering a trusted, efficient, and affordable marketplace. We believe that everyone deserves access to high-quality products and a platform where their voices and values are respected.</p>

        <h2>Why Choose Us?</h2>
        <ul>
            <li>✅ Easy-to-use platform for buyers and sellers</li>
            <li>✅ Secure transactions and verified products</li>
            <li>✅ Fast customer support and service</li>
            <li>✅ A growing marketplace with real opportunities</li>
        </ul>

        <h2>Our Team</h2>
        <p>SpinBzar is developed and maintained by a passionate team of developers, designers, and thinkers who care about technology and the people who use it. We're committed to improving your online shopping journey every day.</p>

        <h2>Contact Us</h2>
        <p>If you have questions, suggestions, or just want to say hello, feel free to reach out via our <a href="contact.php">Contact Page</a> or email us at <a href="mailto:support@spinbzar.com">support@spinbzar.com</a>.</p>

        <p class="thank-you">Thank you for choosing SpinBzar ❤️</p>
    </div>

</body>
</html>
