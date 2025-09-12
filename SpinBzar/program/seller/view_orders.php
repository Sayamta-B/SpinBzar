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
            o.created_at
        FROM orders o
        JOIN products p ON o.product_id = p.product_id
        JOIN payments pay ON o.order_id = pay.order_id
        WHERE o.seller_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpinBzar</title>
    <link rel="stylesheet" href="../../styles/seller_dashboard.css">
    <style>
            th:nth-child(1), td:nth-child(1) { width: 8%; }
            th:nth-child(2), td:nth-child(2) { width: 28%; }
            th:nth-child(3), td:nth-child(3) { width: 12%; }
            th:nth-child(4), td:nth-child(4) { width: 20%; }
            th:nth-child(5), td:nth-child(5) { width: 15%; }
            th:nth-child(6), td:nth-child(6) { width: auto; } 
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SpinBzar</h2>
        <a href="seller_dashboard.php">Overview</a>
        <a href="view_seller_product.php">Products</a>
        <a href="view_orders.php" style="background-color: #A4C17A;">Orders</a>
        <a href="view_payment.php">Payments</a>
        <a href="../login_landing_page.php">Home</a>
        <a href="../logout.php">Logout</a>
  </div>

  <div class="main-content">
    <table border="1" cellpadding="0" cellspacing="0">
        <tr><td colspan="6" style="height:80px; font-weight: bolder;">Orders</td></tr>
            <tr>
                <th style="height:70px;">Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>Ordered On</th>
            </tr>
            
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>Rs. <?= number_format($row['total_price'], 2) ?></td>
                        <td>
                            <?php
                                $order_id = $row['order_id'];
                                $status = $row['order_status'];

                                if ($status == "pending") {
                                    echo "
                                        <form method='post' action='update_order_status.php'>
                                            <input type='hidden' name='order_id' value='$order_id'>
                                            <button type='submit' name='action' value='sent'>Send</button>
                                            <button type='submit' name='action' value='cancelled'>Cancel</button>
                                        </form>
                                    ";
                                } elseif ($status == "sent") {
                                    echo "<span style='color: green; font-weight: bold;'>Sent</span>";
                                } elseif ($status == "cancelled") {
                                    echo "<span style='color: red; font-weight: bold;'>Cancelled</span>";
                                } elseif ($status == "completed") {
                                    echo "<span style='background-color: green; color: white; padding: 3px 8px; border-radius: 4px;'>Completed</span>";
                                } else {
                                    echo "<span style='color: gray;'>$status</span>";
                                }
                            ?>
                        </td>

                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">You have not placed any orders yet.</td></tr>
            <?php endif; ?>
    </table>
    </div>
</body>
</html>
