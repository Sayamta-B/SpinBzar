<?php
/* session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];*/

// Update is_seller to TRUE
include 'db.php';
$sql = "UPDATE users SET is_seller = TRUE WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<script>alert('You are now a seller!'); window.location.href = '../program/seller/seller_dashboard.php';</script>";
} else {
    echo "Error updating user.";
}
?>
