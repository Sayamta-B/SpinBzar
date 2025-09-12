<?php 
/*session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}*/
require '../db.php';

// Get all pending products
$result = $conn->query("SELECT * FROM products WHERE approval_status = 'pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Products</title>
    <link rel="stylesheet" href="../../styles/admin.css">
</head>
<body>
    <div class="sidebar">
        <h2 class="sidebar-title">Admin Panel</h2>
        <ul class="sidebar-nav">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_product.php">Products</a></li>
            <li><a href="pending_product.php" class="active">Pending Products</a></li>
            <li><a href="viewall_orders.php">Orders</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Pending Product Approvals</h1>
        <div class="products-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <?php if (!empty($row['image_url'])): ?>
                        <img src="../seller/<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image" class="product-image">
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                    <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                    <p><strong>Price:</strong> Rs. <?= number_format($row['price'], 2) ?></p>
                    <p><strong>Stock Quantity:</strong> <?= (int)$row['stock_quantity'] ?></p>
                    <p><strong>Seller ID:</strong> <?= (int)$row['seller_id'] ?></p>

                    <form action="approve_reject_product.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <button class="btn approve-btn" name="action" value="approve">Approve</button>
                        <button class="btn reject-btn" name="action" value="reject">Reject</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
