<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
include '../db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo "<script>alert('Product deleted successfully.'); 
            window.location.href = 'http://localhost/E-commerce/program/seller/seller_dashboard.php';
            </script>";
    exit;
} else {
    echo "<script>alert('Error deleting product.'); 
        window.history.back();
        </script>";
}

$stmt->close();
$conn->close();
?>
