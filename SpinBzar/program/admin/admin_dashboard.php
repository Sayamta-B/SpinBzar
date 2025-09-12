<?php
/*session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
include 'pending_product.php' */
include '../db.php';
$totalProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM products"))['count'];
$pendingProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM products WHERE approval_status = 'pending'"))['count'];
$approvedProducts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM products WHERE approval_status = 'approved'"))['count'];
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];
$totalSellers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE user_role = 'admin'"))['count'];
$totalOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM orders"))['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../styles/admin.css">
</head>
<body>
    <div class="sidebar">
  <h2 class="sidebar-title">Admin Panel</h2>
  <ul class="sidebar-nav">
    <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
    <li><a href="admin_product.php">Products</a></li>
    <li><a href="pending_product.php">Pending Products</a></li>
    <li><a href="viewall_orders.php">Orders</a></li>
    <li><a href="../logout.php">Logout</a></li>
  </ul>
</div>
   <div class="main-content">
        <h1>Dashboard Overview</h1>
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Products</h3>
                <p><?= $totalProducts ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Products</h3>
                <p><?= $pendingProducts ?></p>
            </div>
            <div class="stat-card">
                <h3>Approved Products</h3>
                <p><?= $approvedProducts ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?= $totalUsers ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p><?= $totalOrders ?></p>
            </div>
        </div>
    </div>
</body>
</html>