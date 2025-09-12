<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // fixed path
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.price, p.image_url 
                        FROM wishlist w 
                        JOIN products p ON w.product_id = p.product_id 
                        WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SpinBzar - Wishlist</title>
  <link rel="stylesheet" href="../../styles/buyer.css">
  <link rel="stylesheet" href="../../styles/landing_page.css">
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="../login_landing_page.php">Home</a></li>
        <li><a href="../login_product_listing.php">Shop</a></li>
        <li><a href="#">About Us</a></li>
      </ul>
    </nav>

    <div class="logo">
      <h1 style="color: #FFF;">SpinBzar</h1>
    </div>

    <nav>
      <ul>
        <li><a href="wishlist.php" class='active'>Wishlist</a></li>
        <li><a href="cart.php">Cart</a></li>
        <li><a href="../user_profile.php">Profile</a></li>
      </ul>
    </nav>
  </header>

  <div class="main-content">
    <h2 style="text-align:center; margin: 20px 0;">Your Wishlist</h2>

  <div class="wishlist-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="wishlist-card">
               <img src="/SpinBzar/program/seller/<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image" class="product-image">
        <div class="wishlist-details">
          <h3><?= htmlspecialchars($row['product_name']) ?></h3>
          <p class="price">Rs. <?= number_format($row['price'], 2) ?></p>
          <div class="wishlist-actions">
            <form action="move_to_cart.php" method="POST">
              <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
              <button type="submit" class="small-btn">Add to Cart</button>
            </form>
            <form action="remove_from_wishlist.php" method="POST">
              <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
              <button type="submit" class="small-btn">Remove</button>
            </form>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
  </div>
</body>
</html>
