<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT 
            o.order_id,
            p.product_name,
            o.quantity,
            o.total_price,
            o.order_status,
            pay.payment_status,
            pay.payment_method,
            o.created_at
        FROM orders o
        JOIN products p ON o.product_id = p.product_id
        JOIN payments pay ON o.order_id = pay.order_id
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="../../styles/admin.css">
</head>
<body>
    <div class="sidebar">
  <h2 class="sidebar-title">Admin Panel</h2>
  <ul class="sidebar-nav">
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="admin_product.php">Products</a></li>
    <li><a href="pending_product.php">Pending Products</a></li>
    <li><a href="viewall_orders.php" class="active">Orders</a></li>
    <li><a href="../logout.php">Logout</a></li>
  </ul>
</div>
   <div class="main-content">
    <h1>Orders Overview</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Ordered On</th>
            </tr>
        </thead>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>Rs. <?= number_format($row['total_price'], 2) ?></td>
                        <td><?= ucfirst($row['payment_method']) ?></td>
                        <td><?= ucfirst($row['payment_status']) ?></td>
                        <td>
                            <?php
                                $order_id = $row['order_id'];
                                $status = $row['order_status'];

                                if ($status == "sent") {
                                    echo "
                                        <form method='post' action='admin_update_order_payment.php' style='display:inline;'>
                                            <input type='hidden' name='order_id' value='$order_id'>
                                            <button type='submit' name='action' value='completed'>Paid to seller</button>
                                        </form>
                                    ";
                                } elseif ($status == "completed") {
                                    echo "<span style='background-color: green; color: white; padding: 4px 8px; border-radius: 4px;'>Completed</span>";
                                } elseif ($status == "cancelled") {
                                    echo "<span style='background-color: red; color: white; padding: 4px 8px; border-radius: 4px;'>Cancelled</span>";
                                } else {
                                    echo "<span style='background-color: gray; color: white; padding: 4px 8px; border-radius: 4px;'>Pending</span>";
                                }
                            ?>
                        </td>

                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8">You have not placed any orders yet.</td></tr>
            <?php endif; ?>
    </table>
    </div>
</body>
</html>
