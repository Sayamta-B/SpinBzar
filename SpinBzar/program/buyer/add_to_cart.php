<?php
session_start();
require '../db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Check product exists and is approved
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND approval_status = 'approved'");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo "Product not found or not approved.";
        exit;
    }

    if ($product) {
        // Check if product already in cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        $stmt->close();

        if ($cart_result->num_rows > 0) {
            echo "<script>alert('Already added to the cart!');
            window.history.back();
            </script>";
            exit;
        } else {
            // Insert into cart
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: cart.php");
        exit;
    } else {
        echo "Product not found or not approved.";
    }
} else {
    echo "Invalid product ID.";
}
?>
