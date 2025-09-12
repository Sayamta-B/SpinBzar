<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

$update = $conn->prepare("UPDATE orders SET order_status = 'completed', payment_to_seller='paid' WHERE order_id = ?");
$update->bind_param("i", $order_id);

if($update->execute()){
    header("Location: viewall_orders.php");
}

?>