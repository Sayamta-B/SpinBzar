<?php
/*session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}*/
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    $new_status = ($action === 'approve') ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE products SET approval_status = ? WHERE product_id = ?");
    $stmt->bind_param("si", $new_status, $product_id);
    $stmt->execute();

    header("Location: pending_product.php");
    exit();
}
?>
