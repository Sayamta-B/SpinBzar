<?php
require '../db.php'; // include your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    // Validate
    if (!in_array($action, ['sent', 'cancelled'])) {
        die("Invalid action.");
    }

    if($action=='sent'){
        // Prepare and execute update
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'sent', updated_at = NOW() WHERE order_id = ?");
        $stmt->bind_param("i",  $order_id);

        if ($stmt->execute()) {
            header("Location: view_orders.php");
        } else {
            echo "Error updating order.";
        }
    }
    else{
        // Prepare and execute update
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled', payment_to_seller='failed', updated_at = NOW() WHERE order_id = ?");
        $stmt->bind_param("i",  $order_id);

        if ($stmt->execute()) {
            header("Location: view_orders.php");
        } else {
            echo "Error updating order.";
        }
    }

    

    $stmt->close();
    $conn->close();
}
?>
