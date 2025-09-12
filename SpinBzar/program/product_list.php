<?php

require 'db.php';
$search = $_GET['search'] ?? '';

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpinBzar - Products by Category</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../styles/landing_page.css">
    <link rel="stylesheet" href="../styles/product_list.css">
</head>
<body>

<div class="heading">
    <nav>
        <ul>
            <li><a href="landing_page.php">Home</a></li>
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
</div>

<h2>Browse Products by Category Or Name</h2>

<!-- Search form redirects to searching.php -->
<form method="GET" action="searching1.php">
    <input type="text" name="search" placeholder="Search products or categories..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<!-- Display Products Grouped by Category -->
<?php while ($cat = $categories->fetch_assoc()): ?>
    <div class="category-section">
        <h3><?= htmlspecialchars($cat['category_name']) ?></h3>

        <?php
        $sql = "SELECT * FROM products WHERE approval_status = 'approved' AND category_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cat['category_id']);
        $stmt->execute();
        $products = $stmt->get_result();
        ?>

        <?php if ($products->num_rows === 0): ?>
            <p class="no-products">No products in this category.</p>
        <?php else: ?>
            <div class="product-container">
                <?php while ($row = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="/SpinBzar/program/seller/<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" class="product-image">
                        <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                        <p>Rs. <?= number_format($row['price'], 2) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

</body>
</html>
