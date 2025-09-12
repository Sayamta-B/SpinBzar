<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';  // default to user if not set

$stmt = $conn->prepare("SELECT username, email, full_name, phone_number, address, user_role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$role = $user['user_role'];  // ensure correct value

$stmt = $conn->prepare("SELECT COUNT(*) AS total_orders, IFNULL(SUM(total_price),0) AS total_spent FROM orders WHERE buyer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$buyer_summary = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT COUNT(DISTINCT product_id) AS total_products_sold, IFNULL(SUM(quantity), 0) AS total_quantity_sold, IFNULL(SUM(total_price), 0) AS total_earned FROM orders WHERE seller_id = ? AND order_status = 'completed'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$seller_summary = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $role === 'admin' ? 'Admin Profile' : 'User Profile' ?></title>
   <link rel="stylesheet" href="../styles/landing_page.css">
   <link rel="stylesheet" href="../styles/user_profile.css">
</head>
<body>
<div class="sidebar">
        <h2>SpinBzar</h2>
        <a href="user_dashboard.php" style="background-color: #A4C17A;">Profile</a>
        <a href="buyer/my_payments.php">My Payment</a>
        <a href="buyer/my_orders.php">My Orders</a>
        <a href="seller/seller_dashboard.php">My Seller Dashboard</a>
        <a href="login_landing_page.php">Home</a>
        <a href="logout.php">Logout</a>
  </div>
<div class="main-content">
  <center><h1> Profile </h1></center>
  <div class="profile-card">
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
  </div>
  <div class="profile_details">
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone_number']) ?></p>
    <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($user['address'])) ?></p>
  </div><br><br><br>

<?php if ($role === 'admin'): ?>
  <div class="section-title">Admin Privileges</div>
  <div class="profile-card">
    <p>You have admin rights. You can approve/reject products, manage users, and more.</p>
  </div>
<?php endif; ?>

<center><h1>Buying Summary<h1></h1></center><br>
<div class="stats-container">
  <div class="stat-card">
    <h3>Total Orders Bought</h3>
    <p><?= $buyer_summary['total_orders'] ?></p>
  </div>
  <div class="stat-card">
    <h3>Total Amount Spent</h3>
    <p>$<?= number_format($buyer_summary['total_spent'], 2) ?></p>
  </div>
</div>

<center><h1>Selling Summary<h1></h1></center><br>
<div class="stats-container">
  <div class="stat-card">
    <h3>Total Products Sold</h3>
    <p><?= $seller_summary['total_products_sold'] ?></p>
  </div>
  <div class="stat-card">
    <h3>Total Quantity Sold</h3>
    <p><?= $seller_summary['total_quantity_sold'] ?></p>
  </div>
  <div class="stat-card">
    <h3>Total Amount Earned</h3>
    <p>$<?= number_format($seller_summary['total_earned'], 2) ?></p>
  </div>
</div>
</div>
</body>
</html>
