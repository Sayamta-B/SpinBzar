<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

$update = $conn->prepare("UPDATE orders SET order_status = 'cancelled', updated_at = NOW() WHERE order_id = ?");
$update->bind_param("i", $order_id);

// First get the quantity and product_id for the order
$stmt = $conn->prepare("SELECT product_id, quantity FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$stmt->bind_result($product_id, $quantity);
$stmt->fetch();
$stmt->close();

// Then update the product stock
$updateStock = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE product_id = ?");
$updateStock->bind_param("ii", $quantity, $product_id);
$updateStock->execute();

if($update->execute() && $updatStock->execute()){
    header("Location: my_orders.php");
    exit;
}
?>