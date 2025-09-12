<?php
require '../program/db.php';
$search = $_GET['search'] ?? '';

if (empty($search)) {
    // Redirect back to product_list.php if no search term
    header("Location: product_list.php");
    exit;
}
// Search query: product_name OR category_name matches search term
$sql = "SELECT p.*, c.category_name 
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        WHERE (p.product_name LIKE CONCAT('%', ?, '%') OR c.category_name LIKE CONCAT('%', ?, '%'))
        AND p.approval_status = 'approved'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpinBzar - Search Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../styles/landing_page.css">
    <link rel="stylesheet" href="../styles/product_list.css">
</head>
<body>

<div class="heading">
    <nav>
        <ul>
            <li><a href="login_landing_page.php">Home</a></li>
            <li><a href="login_product_listing.php">Shop</a></li>
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

<h2>Search Results for "<?= htmlspecialchars($search) ?>"</h2>

<form method="GET" action="searching.php">
    <input type="text" name="search" placeholder="Search products or categories..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
    <a href="product_list.php" style="margin-left: 10px;" >Clear Search</a>
</form>

<div class="product-list">
    <?php if ($products->num_rows === 0): ?>
        <p>No products found for your search.</p>
    <?php else: ?>
        <?php while ($row = $products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image">
                <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                <p>Category: <?= htmlspecialchars($row['category_name']) ?></p>
                <p>Rs. <?= number_format($row['price'], 2) ?></p>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
