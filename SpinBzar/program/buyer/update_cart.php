<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = $_POST['id'] ?? null;

    switch ($action) {
        case 'increase':
            if ($product_id) {
$stmt = $conn->prepare("
    UPDATE cart c 
    JOIN products p ON c.product_id = p.product_id
    SET c.quantity = c.quantity + 1 
    WHERE c.user_id = ? 
      AND c.product_id = ? 
      AND c.quantity < p.stock_quantity
");
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                $stmt->close();
            }
            break;

        case 'decrease':
            if ($product_id) {
                // Ensure quantity does not go below 1
                $stmt = $conn->prepare("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                $stmt->close();
            }
            break;

        case 'remove':
            if ($product_id) {
                $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                $stmt->close();
            }
            break;


        default:
            echo "Invalid action.";
            exit;
    }
}

header("Location: cart.php"); // Redirect back to cart after update
exit;
?>
