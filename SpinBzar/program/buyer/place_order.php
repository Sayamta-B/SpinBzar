<?php 
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$shipping_address = trim($_SESSION['shipping_address']);
$payment_method = $_SESSION['payment_method'];
$payment_status = ($payment_method === 'esewa') ? 'completed' : 'pending';

if (empty($shipping_address) || empty($payment_method)) {
    die("Please provide shipping address and payment method.");
}

$sub_total=$_SESSION['amount'];
$shipping_fees = $_SESSION['product_delivery_charge'];
$tax = $_SESSION['tax_amount'];
$grand_total = $_SESSION['total_amount'];

$query = "SELECT c.*, p.price, p.seller_id 
          FROM cart c 
          JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    die("No matching cart items found.");
}

// Prepare insert statements
$order_stmt = $conn->prepare("INSERT INTO orders (buyer_id, seller_id, product_id, quantity, total_price, tax, shipping_fees, shipping_address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
$payment_stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method, amount, payment_status) VALUES (?, ?, ?, ?)");
$cart_stmt = $conn->prepare("UPDATE products p
JOIN cart c ON p.product_id = c.product_id
SET p.stock_quantity = p.stock_quantity - c.quantity
WHERE c.user_id = ?;
");

foreach ($cart_items as $item) {
    $product_total = $item['price'] * $item['quantity'];

    // Insert into orders
    $order_stmt->bind_param("iiiiddds", $user_id, $item['seller_id'], $item['product_id'], $item['quantity'], $sub_total, $tax, $shipping_fees, $shipping_address);
    $order_stmt->execute();
    $order_id = $order_stmt->insert_id;

    // Insert payment
    $payment_stmt->bind_param("isds", $order_id, $payment_method, $grand_total, $payment_status);
    $payment_stmt->execute();

    //update products quantity
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
}
$order_stmt->close();
$payment_stmt->close();

// Delete selected items
$delete_query = "DELETE FROM cart WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();
$delete_stmt->close();

$conn->close();

header("Location: order_sucess.php");
exit();
?>
