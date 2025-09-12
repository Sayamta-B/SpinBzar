<?php
session_start();
require '../db.php';

$user_id = $_SESSION['user_id'];

$query = "
    SELECT p.payment_id, p.order_id, pro.product_name, p.payment_method, p.amount, p.payment_status, p.created_at
    FROM payments p
    JOIN orders o ON p.order_id = o.order_id
    JOIN products pro ON o.product_id = pro.product_id
    WHERE o.buyer_id = ?
    ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpinBzar</title>
    <link rel="stylesheet" href="../../styles/user_profile.css">
    

</head>
<body>
    <div class="sidebar">
        <h2>SpinBzar</h2>
        <a href="../user_profile.php">Profile</a>
        <a href="my_payments.php" style="background-color: #A4C17A;">My Payment</a>
        <a href="my_orders.php">My Orders</a>
        <a href="../seller/seller_dashboard.php">My Seller Dashboard</a>
        <a href="../login_landing_page.php">Home</a>
        <a href="../logout.php">Logout</a>
  </div>
<div class="main-content">
    <h1>My Orders</h1>
<table border="1" cellpadding="0" cellspacing="0">
    <tr>
        <th colspan="7" style="height:80px;">Payment Received</th>
    </tr>
    <tr>
        <th>Payment ID</th>
        <th>Product Name</th>
        <th>Order ID</th>
        <th>Method</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['payment_id'] ?></td>
            <td><?= $row['product_name'] ?></td>
            <td><?= $row['order_id'] ?></td>
            <td><?= ucfirst($row['payment_method']) ?></td>
            <td>Rs. <?= number_format($row['amount'], 2) ?></td>
            <td>
                <?php
                if ($row['payment_status'] === 'completed') {
                    echo "<span style='color: green;'>Completed</span>";
                } elseif ($row['payment_status'] === 'failed') {
                    echo "<span style='color: red;'>Failed</span>";
                } else {
                    echo "<span style='color: gray;'>Pending</span>";
                }
                ?>
            </td>
            <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
        </tr>
    <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">You have not received any payments yet.</td></tr>
    <?php endif; ?>
</table>
</div>    
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
