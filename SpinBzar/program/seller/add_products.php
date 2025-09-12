<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$seller_id = $_SESSION['user_id'];
include '../db.php';

// Fetch categories
$categories = [];
$result = $conn->query("SELECT category_id, category_name FROM categories");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];

    // Validate file upload
    $image_url = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = time() . "_" . basename($_FILES['product_image']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $image_url = 'uploads/' . $file_name;
        } else {
            echo "<script>alert('Image upload failed.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Please upload a product image.'); window.history.back();</script>";
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products 
        (seller_id, product_name, description, price, stock_quantity, category_id, created_at, updated_at, image_url)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");

    if (!$stmt) {
        echo "<script>alert('Database error: prepare failed.'); window.history.back();</script>";
        exit;
    }

    $stmt->bind_param("issdiis", 
        $seller_id, $product_name, $description, $price, $stock_quantity, $category_id, $image_url
    );

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully.'); window.location.href = 'view_seller_product.php';</script>";
    } else {
        echo "<script>alert('Error adding product: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Product - SpinBzar</title>
  <link rel="stylesheet" href="../../styles/seller_dashboard.css">
  <script>
    function validate() {
        const price = parseFloat(document.getElementById("price").value.trim());
        const stock = parseInt(document.getElementById("stock_quantity").value.trim());

        if (price < 0) {
            alert("Price cannot be negative.");
            return false;
        }
        if (stock < 0) {
            alert("Stock quantity cannot be negative.");
            return false;
        }
        return true;
    }
  </script>
</head>
<body>
  <div class="sidebar">
    <h2>SpinBzar</h2>
    <a href="seller_dashboard.php">Overview</a>
    <a href="view_seller_product.php" style="background-color: #A4C17A;">Products</a>
    <a href="view_orders.php">Orders</a>
    <a href="view_payment.php">Payments</a>
    <a href="../landing_page.php">Home</a>
    <a href="../logout.php">Logout</a>
  </div>

  <div class="main-content">
    <div class="sub-content">
      <h3>Add Product</h3><br/>
      <form method="POST" enctype="multipart/form-data" onsubmit="return validate();">
        <input type="text" name="product_name" placeholder="Product Name" required />
        <textarea name="description" placeholder="Product Description" required></textarea>
        <input type="number" name="price" id="price" placeholder="Price" required />
        <input type="number" name="stock_quantity" id="stock_quantity" placeholder="Stock Quantity" required />
        <select name="category_id" required>
          <option value="">--Select Category--</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat['category_id']) ?>">
              <?= htmlspecialchars($cat['category_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="file" name="product_image" accept="image/*" required />
        <button class="btn" type="submit">Add Product</button>
      </form>
    </div>
  </div>
</body>
</html>

