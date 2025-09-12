<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
include '../db.php';

if (!isset($_GET['id'])) {
    die("Product ID missing.");
}

$product_id = (int)$_GET['id'];

// Fetch product data
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpinBzar</title>
    <link rel="stylesheet" href="../../styles/seller_dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>SpinBzar</h2>
        <a href="http://localhost/E-commerce/program/seller/seller_dashboard.php" >Overview</a>
        <a href="http://localhost/E-commerce/program/seller/view_seller_product.php" style="background-color: #A4C17A;">Products</a>
        <a href="http://localhost/E-commerce/program/seller/view_orders.php">Orders</a>
        <a href="http://localhost/E-commerce/program/seller/view_payment.php">Payments</a>
        <a href="../landing_page.php">Home</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main-content">
        <div class="sub-content">
            <h2>Update Product</h2>
            <form action="update_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

                <input type="text" name="product_name" placeholder="Product name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

                <textarea name="description" placeholder="description" required><?= htmlspecialchars($product['description']) ?></textarea>

                <input type="number" name="price" placeholder="Price" step="0.01" value="<?= $product['price'] ?>" required>

                <label>Stock Quantity</label>
                <input type="number" name="stock" value="<?= $product['stock_quantity'] ?>" required>

                <label>Category ID</label>
                <input type="number" name="category" value="<?= $product['category_id'] ?>" required>
                <!--<select id="category_id" required>
                    <?php
                        // $cat_result = $cat_conn->query("SELECT category_id, category_name FROM categories");
                        // if ($cat_result->num_rows > 0) {
                        //     while ($row = $cat_result->fetch_assoc()) {
                        //         $selected = $row['category_id'] == $product['category_id'] ? 'selected' : '';
                        //         echo "<option value='{$row['category_id']}' $selected>" . htmlspecialchars($row['category_name']) . "</option>";
                        //     }
                        // } else {
                        //     echo "<option disabled>No categories found</option>";
                        // }
                        // $cat_conn->close();
                    ?>
                </select>-->

                <label>Product Image (Optional)</label>
                <input style="background-color: white;" type="file" name="image_url">

                <button type="submit" class="btn">Update Product</button>
            </form>
        </div>
    </div>    
</body>
</html>
