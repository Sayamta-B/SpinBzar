<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Simulated login session (replace with actual session data)
$seller_id = $_SESSION['user_id'];

$sql = "SELECT * FROM products WHERE seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SpinBzar</title>
    <link rel="stylesheet" href="../../styles/seller_dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>SpinBzar</h2>
        <a href="seller_dashboard.php" >Overview</a>
        <a href="view_seller_product.php" style="background-color: #A4C17A;">Products</a>
        <a href="view_orders.php">Orders</a>
        <a href="view_payment.php">Payments</a>
        <a href="../login_landing_page.php">Home</a>
        <a href="../logout.php">Logout</a>
  </div>

  <div class="main-content">
    <a class="add-btn" href="add_products.php">+ Add Product</a>
    <div class="products-container">
        <?php if (count($products) === 0): ?>
            <p>No products added yet.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($product['image_url'] ?: 'default.png') ?>" alt="Product Image">
                    <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p class="price">Rs. <?= number_format($product['price'], 2) ?></p>
                    <p>Status: <strong><?= htmlspecialchars($product['approval_status']) ?></strong></p>

                        <?php if ($product['approval_status'] === 'approved'): ?>
                            <div class="actions">
                                <a href="update_form_product.php?id=<?= $product['product_id'] ?>">Edit</a>
                                <a href="delete_product.php?id=<?= $product['product_id'] ?>" onclick="return confirm('Are you sure to delete this product?');">Delete</a>
                            </div>
                        <?php else: ?>
                            <p>(edit/delete)locked</p>
                        <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    </div>
</body>
</html>
