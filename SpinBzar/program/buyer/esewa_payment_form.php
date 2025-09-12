<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

    $_SESSION['shipping_address'] = $_POST['shipping_address'];
    $_SESSION['payment_method'] = $_POST['payment_method'];
    $_SESSION['amount'] = $_POST['total'];
    $_SESSION['product_delivery_charge'] = $_POST['calculated_shipping_fee'];
    $_SESSION['tax_amount'] = $_POST['calculated_tax'];
    $_SESSION['total_amount'] = $_POST['calculated_grand_total'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $total_amount = $_POST['calculated_grand_total'];
    $shipping_address = $_POST['shipping_address'];
    $shipping_fee = $_POST['calculated_shipping_fee'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['total'];
    $tax_amount= $_POST['calculated_tax'];

if ($payment_method=="cash_on_delivery") {
    header("Location: place_order.php");
    exit();
}

if ($payment_method=="khalti") {
    header("Location: khalti-checkout.php");
    exit();
}

$transaction_uuid = 'TX' . time();
$product_code = "EPAYTEST";
$product_service_charge = 0;
$product_delivery_charge = $shipping_fee;
$success_url = "http://localhost/SpinBzar/program/buyer/place_order.php";
$failure_url = "http://localhost/SpinBzar/program/buyer/order_failure.php";
$secret_key = "8gBm/:&EnhH.1/q"; // Demo key

// Signed fields must be in this order
$signed_field_names = "total_amount,transaction_uuid,product_code";

// Build raw string
$raw_string = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";


// Generate signature
$signature = base64_encode(hash_hmac('sha256', $raw_string, $secret_key, true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>eSewa Test Payment</title>
</head>
<body>
  <!-- <h2>eSewa Test Payment (PHP Version)</h2> -->
  <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" id="esewa-form">
    <input type="hidden" name="amount" value="<?= $amount ?>">
    <input type="hidden" name="tax_amount" value="<?= $tax_amount ?>">
    <input type="hidden" name="total_amount" value="<?= $total_amount ?>">
    <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid ?>">
    <input type="hidden" name="product_code" value="<?= $product_code ?>">
    <input type="hidden" name="product_service_charge" value="<?= $product_service_charge ?>">
    <input type="hidden" name="product_delivery_charge" value="<?= $product_delivery_charge ?>">
    <input type="hidden" name="success_url" value="<?= $success_url ?>">
    <input type="hidden" name="failure_url" value="<?= $failure_url ?>">
    <input type="hidden" name="signed_field_names" value="<?= $signed_field_names ?>">
    <input type="hidden" name="signature" value="<?= $signature ?>">
  </form>
  <script>
    window.onload = function () {
        document.getElementById('esewa-form').submit();
    };
</script>
</body>
</html>
