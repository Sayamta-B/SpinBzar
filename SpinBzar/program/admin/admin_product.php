<?php
include '../db.php'; // your DB connection

// Fetch categories for dropdown
$category_result = $conn->query("SELECT * FROM categories");

// Get filters
$search = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? '';
$seller_id = $_GET['seller_id'] ?? ''; // Optional: You can set this if you're filtering by seller

// Build dynamic query
$query = "SELECT p.*, c.category_name FROM products p 
          JOIN categories c ON p.category_id = c.category_id 
          WHERE 1=1";

$params = [];
$types = "";

// Search by product name
if (!empty($search)) {
    $query .= " AND p.product_name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

// Filter by category
if (!empty($category_id)) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

// Optional: Filter by seller
if (!empty($seller_id)) {
    $query .= " AND p.seller_id = ?";
    $params[] = $seller_id;
    $types .= "i";
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet" href="../../styles/admin.css">
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
  <h2 class="sidebar-title">Admin Panel</h2>
  <ul class="sidebar-nav">
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="admin_product.php" class="active">Products</a></li>
    <li><a href="pending_product.php">Pending Products</a></li>
    <li><a href="viewall_orders.php">Orders</a></li>
    <li><a href="../logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">

<h2>All Products</h2>

<!-- Filter Form -->
<form method="GET">
    <input type="text" name="search" placeholder="Search by product name" value="<?= htmlspecialchars($search) ?>">

    <select name="category">
        <option value="">All Categories</option>
        <?php while ($cat = $category_result->fetch_assoc()): ?>
            <option value="<?= $cat['category_id'] ?>" <?= ($cat['category_id'] == $category_id) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['category_name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <!-- Optional hidden seller_id -->
    <!-- <input type="hidden" name="seller_id" value="<?= $seller_id ?>"> -->

    <button type="submit">Search</button>
</form>

<!-- Product Table -->
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Status</th>
        <th>Image</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['product_id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td>Rs. <?= number_format($row['price'], 2) ?></td>
                <td><?= $row['stock_quantity'] ?></td>
                <td><?= ucfirst($row['approval_status']) ?></td>
                <td>
                    <?php if (!empty($row['image_url'])): ?>
                        <img src="/SpinBzar/program/seller/<?= htmlspecialchars($row['image_url']) ?>" width="50">
                    <?php else: ?>
                        No image
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">No products found.</td></tr>
    <?php endif; ?>
</table>
</div>
</body>
</html>
