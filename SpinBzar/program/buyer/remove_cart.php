<?php
require '../db.php';
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if product_id is provided in GET
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Prepare SQL to remove item from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        echo "<script> alert('Item deleted from cart.');
        window.location.href ='cart.php';
        </script>";
        exit;
    } else {
        echo "Error removing item.";
    }

    $stmt->close();
} else {
    echo "No product selected to remove.";
}
?>
