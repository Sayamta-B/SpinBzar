<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
include '../db.php';

// Validate required fields
if (
    !isset($_POST['product_id'], $_POST['product_name'], $_POST['description'],
            $_POST['price'], $_POST['stock'], $_POST['category']) ||
    !is_numeric($_POST['price']) || !is_numeric($_POST['stock'])
) {
    echo "<script>alert('Invalid input.'); window.history.back();</script>";
    exit;
}

$product_id   = (int)$_POST['product_id'];
$product_name = trim($_POST['product_name']);
$description  = trim($_POST['description']);
$price        = (float)$_POST['price'];
$stock        = (int)$_POST['stock'];
$category_id  = (int)$_POST['category'];
$image_url    = null;

// Handle image upload (if any)
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $imageName = time() . "_" . basename($_FILES["product_image"]["name"]);
    $targetFile = $targetDir . $imageName;

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
        $image_url = $targetFile;
    }
}

// Build SQL query (with or without image update)
if ($image_url) {
    $sql = "UPDATE products 
            SET product_name=?, description=?, price=?, stock_quantity=?, category_id=?, image_url=?, updated_at=NOW() 
            WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdissi", $product_name, $description, $price, $stock, $category_id, $image_url, $product_id);
} else {
    $sql = "UPDATE products 
            SET product_name=?, description=?, price=?, stock_quantity=?, category_id=?, updated_at=NOW() 
            WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $product_name, $description, $price, $stock, $category_id, $product_id);
}

// Execute and redirect
if ($stmt->execute()) {
    echo "<script>alert('Product updated successfully.'); window.location.href = 'seller_dashboard.php';</script>";
} else {
    echo "<script>alert('Error updating product.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
