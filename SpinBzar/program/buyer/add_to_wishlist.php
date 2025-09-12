<?php
session_start();
require '../db.php'; // Adjust path if needed

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? null;

if ($product_id) {
    // Prevent duplicates
    $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

header("Location: wishlist.php");
exit;
?>
