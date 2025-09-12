<?php
include '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Product count
$product_result = $conn->query("SELECT COUNT(*) AS total_products FROM products WHERE seller_id = $user_id");
$product_count = $product_result->fetch_assoc()['total_products'] ?? 0;

// Order count
$order_result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders WHERE seller_id = $user_id");
$order_count = $order_result->fetch_assoc()['total_orders'] ?? 0;

// Payment count
$payment_result = $conn->query("SELECT COUNT(*) AS total_payments FROM payments WHERE order_id IN (SELECT order_id FROM orders WHERE seller_id = $user_id)");
$payment_count = $payment_result->fetch_assoc()['total_payments'] ?? 0;

// Profit calculation (example: sum of payments)
$profit_result = $conn->query("SELECT SUM(amount) AS total_profit FROM payments WHERE order_id IN (SELECT order_id FROM orders WHERE seller_id = $user_id AND order_status='completed')");
$total_profit = $profit_result->fetch_assoc()['total_profit'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SpinBzar</title>
  <link rel="stylesheet" href="../../styles/seller_dashboard.css">
  <style>
    .dashboard-cards {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .card {
      background: #f5f5f5;
      padding: 20px;
      width: 280px;
      border-radius: 10px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      text-align: center;
      text-decoration: none;
      color: black;
      transition: background 0.3s;
    }
    .card:hover {
      background: #e2efc7;
    }
    .card h3 {
      margin: 0;
      font-size: 18px;
    }
    .card p {
      font-size: 24px;
      margin-top: 10px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>SpinBzar</h2>
    <a href="seller_dashboard.php" style="background-color: #A4C17A;" class="active">Overview</a>
    <a href="view_seller_product.php">Products</a>
    <a href="view_orders.php">Orders</a>
    <a href="view_payment.php">Payments</a>
    <a href="../login_landing_page.php">Home</a>
    <a href="../logout.php">Logout</a>
  </div>

  <div class="main-content">
    <br>  <h2>Dashboard Overview</h2><br><br><br><br>
    <div class="dashboard-cards">

      <a class="card" href="view_seller_product.php">
        <h3>Manage Products</h3>
        <p><?= $product_count ?></p>
      </a>

      <a class="card" href="view_orders.php">
        <h3>Orders</h3>
        <p><?= $order_count ?></p>
      </a>

      <a class="card" href="view_payment.php">
        <h3>Payments</h3>
        <p><?= $payment_count ?></p>
      </a>

      <div class="card" style="width: 800px;">
        <h3>Revenue from SpinBzar</h3>
        <p>Rs. <?= number_format($total_profit, 2) ?></p>
      </div>

    </div>
  </div>
</body>
</html>
