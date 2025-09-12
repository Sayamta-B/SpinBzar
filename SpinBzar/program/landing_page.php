<?php
include 'db.php';
$product_stmt = $conn->prepare("SELECT * FROM products WHERE approval_status = 'approved' AND stock_quantity != 0");
$product_stmt->execute();
$products = $product_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SpinBzar - Landing Page</title>
    <link rel="stylesheet" href="../styles/landing_page.css" />
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="landing_page.php" class="active">Home</a></li>
            <li><a href="product_list.php">Shop</a></li>
            <li><a href="#">About Us</a></li>
        </ul>
    </nav>

    <div class="logo">
        <h1 style="color: #FFF;">SpinBzar</h1>
    </div>

    <nav>
        <ul>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
</header>

<section id="CTA_section">
    <div class="header_CTA">
        <h1>Sell what you have,</h1>
        <h1>Buy what you need</h1>
        <button id="cta-btn">Explore Now</button>
        <script>
            document.getElementById('cta-btn').addEventListener('click', () => {
                document.getElementById('how-it-works').scrollIntoView({ behavior: 'smooth' });
            });
        </script>
    </div>
    <div class="header_image">
        <img src="../photos/header_image.png" alt="header_image" />
    </div>
</section>

<section id="category-list">
    <form action="searching.php" method="GET">
        <button type="submit" name="category" value="Electronics" class="category big">
            <img src="../photos/electronics.jpg" alt="Electronics">
        </button>
    </form>

    <div class="smallcat">
        <form action="searching.php" method="GET">
            <button type="submit" name="category" value="Clothing" class="category small">
                <img src="../photos/cloths.jpg" alt="Clothing">
            </button>
        </form>
        <form action="searching.php" method="GET">
            <button type="submit" name="category" value="Home Goods" class="category small">
                <img src="../photos/homegoods.jpg" alt="Home Goods">
            </button>
        </form>
    </div>
</section>

<section id="how-it-works">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">
            <h3>Sign Up</h3>
            <p>Create your free account to start buying or selling instantly.</p>
            <button class="small-btn" onclick="window.location.href='register.php'">Sign up</button>
        </div>
        <div class="step">
            <h3>Sell Your Product</h3>
            <p>Upload pictures, set a price, and describe your item in a few clicks.</p>
            <button class="small-btn" onclick="window.location.href='login.php'">Go to Dashboard</button>
        </div>
        <div class="step">
            <h3>Shop & Sell</h3>
            <p>Browse other users’ products, message sellers, and make deals securely.</p>
            <button id="shop-btn" class="small-btn">Explore Now</button>
            <script>
                document.getElementById('shop-btn').addEventListener('click', () => {
                    document.getElementById('featured_products').scrollIntoView({ behavior: 'smooth' });
                });
            </script>
        </div>
    </div>
</section>

<section id="featured_products">
    <h2>Featured Products</h2>
    <div class="products-container">
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="card">
                <?php if (!empty($product['image_url'])): ?>
                    <!-- ✅ Updated image path to relative -->
                    <img src="seller/<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" class="product-image">
                <?php else: ?>
                    <img src="../photos/default.jpg" alt="No image available" class="product-image" />
                <?php endif; ?>
                <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <p>Rs. <?= number_format($product['price'], 2) ?></p>
                <button class="buy-btn" onclick="alert('Please login to add items to cart.')">Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="our-values">
    <div class="value-cards">
        <div class="value-card">
            <h3>Recycle & Reuse</h3>
            <p>Give items a second life.</p>
        </div>
        <div class="value-card">
            <h3>Empower People</h3>
            <p>Connect with real individuals — buy and sell directly in a trusted community.</p>
        </div>
        <div class="value-card">
            <h3>Protect the Planet</h3>
            <p>Reduce waste through smart, sustainable shopping.</p>
        </div>
    </div>
    <p class="value-intro">We’re more than a marketplace — we care about people and the planet.</p>
</section>

<footer>
    <ul>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</footer>

<a href="sell.html" class="floating-btn" title="Dashboard">+</a>

</body>
</html>
