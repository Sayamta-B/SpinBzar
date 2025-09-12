<?php
session_start();
include 'db.php';

// Check login
$user_id = $_SESSION['user_id'] ?? null;
$is_logged_in = !is_null($user_id);

// Fetch user's cart items
$cart_items = [];
if ($user_id) {
    $stmt = $conn->prepare("SELECT product_id FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = (int)$row['product_id'];
    }
    $stmt->close();
}

// Fetch latest 3 approved products by created_at date (newest first)
$product_stmt = $conn->prepare("SELECT * FROM products WHERE approval_status = 'approved' AND stock_quantity!=0 AND seller_id!=? ORDER BY created_at DESC LIMIT 6");
$product_stmt->bind_param("i", $user_id);
$product_stmt->execute();
$prod = $product_stmt->get_result();

$categories = $conn->query("SELECT DISTINCT c.*
                                    FROM categories c
                                    JOIN products p ON c.category_id = p.category_id;
                                    ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpinBzar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <img src="../photos/header_image.png" alt="header_image">
    </div>
</section>


<section class="category-list">
    <form class="category-list" action="searching.php" method="GET">
        <?php while ($cat = $categories->fetch_assoc()): ?>
    <div class="category-section">
        <h3><?= htmlspecialchars($cat['category_name']) ?></h3>

        <?php
        $sql = "SELECT * FROM products WHERE approval_status = 'approved' AND stock_quantity!=0 AND seller_id!=? AND category_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $cat['category_id']);
        $stmt->execute();
        $products = $stmt->get_result();
        ?>

        <?php if ($products->num_rows === 0): ?>
            <p class="no-products">No products in this category.</p>
        <?php else: ?>
            <div class="product-container">
                <?php while ($row = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <img style="width:200px;" src="/SpinBzar/program/seller/<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image" class="product-image">
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
        </form>
    </div>
</section>

<section id="how-it-works">
    <h2>How It Works</h2>
    <div class="steps">
        <div class="step">
            <h3>Sell Your Product</h3>
            <p>Upload pictures, set a price, and describe your item in a few clicks.</p>
            <button id="seller-btn" class="small-btn" onclick="window.location.href='./seller/seller_dashboard.php'">Go to Dashboard</button>
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
    <div class="products-container" style="justify-content:center;">
        <?php while ($prods = $prod->fetch_assoc()): ?>
            <div class="card" style="width:320px;">
                <img src="/SpinBzar/program/seller/<?= htmlspecialchars($prods['image_url']) ?>" alt="Product Image" class="product-image">
                <h3><?= htmlspecialchars($prods['product_name']) ?></h3>
                <p>Rs. <?= number_format($prods['price'], 2) ?></p>

                <div class="button-group">
                    <?php if (in_array($prods['product_id'], $cart_items, true)): ?>
                        <a class="buy-btn disabled" style="padding: 20px 24px">Added to Cart</a>
                    <?php else: ?>
                        <a href="buyer/add_to_cart.php?id=<?= $prods['product_id'] ?>" class="buy-btn" style="background-color:green; padding:20px 32px; text-align:center;">Add to Cart</a>
                    <?php endif; ?>

                    <span class="fp-separator">|</span>

                    <a href="buyer/add_to_wishlist.php?id=<?= $prods['product_id'] ?>" class="buy-btn" style="background-color:white; color:black; border:1px solid green; padding:20px 20px; text-align:center;">Add to Wishlist</a>
                </div>
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

<a href="seller/seller_dashboard.php" class="floating-btn" title="Dashboard">+</a>

</body>
</html>
