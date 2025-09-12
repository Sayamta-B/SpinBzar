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
        WHERE o.buyer_id = ?
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
    <title>My Orders</title>
    <link rel="stylesheet" href="../../styles/orders.css">
    <link rel="stylesheet" href="../../styles/user_profile.css">
</head>
<body>
     <div class="sidebar">
        <h2>SpinBzar</h2>
        <a href="../user_profile.php">Profile</a>
        <a href="my_payments.php">My Payment</a>
        <a href="my_orders.php" style="background-color: #A4C17A;">My Orders</a>
        <a href="../seller/seller_dashboard.php">My Seller Dashboard</a>
        <a href="../login_landing_page.php">Home</a>
        <a href="../logout.php">Logout</a>
  </div>
<div class="main-content">
    <h1>My Orders</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th colspan="8" style="height:70px;">Orders Given</th>
            </tr>
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
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>Rs. <?= number_format($row['total_price'], 2) ?></td>
                        <td><?= ucfirst($row['payment_method']) ?></td>
                        <td><?= ucfirst($row['payment_status']) ?></td>
                        <td><?php
                                $order_id = $row['order_id'];
                                $status = $row['order_status'];

                                if ($status == "pending") {
                                        echo "<span style='color: gray; font-weight: bold;'>Sent</span>";
                                } elseif ($status == "sent") {
                                    echo "
                                        <form method='post' action='update_order_status.php'>
                                                <input type='hidden' name='order_id' value='$order_id'>
                                                <button type='submit' name='action' value='cancelled'>Cancel</button>
                                            </form>
                                    ";
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
                <tr><td colspan="8">You have not placed any orders yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
            </div>
</body>
</html>
