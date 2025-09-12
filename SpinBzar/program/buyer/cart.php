<?php
require '../db.php'; 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total = 0;

// Fetch cart items from DB
$stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.product_name, p.price, p.image_url
                        FROM cart c
                        JOIN products p ON c.product_id = p.product_id
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_all(MYSQLI_ASSOC);
$quantity = [];//array to store tickets available
    while ($row = $result->fetch_assoc()) {
        $quantity[$row['product_id']] = $row['quantity'];
    }
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpinBzar</title>
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
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="../user_profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

<div class="main-content">
    <center><h1>Shopping Cart</h1></center>
    <br><br>
    <?php if (empty($cart)): ?>
        <p style="text-align:center;">Your cart is empty.</p>
    <?php else: ?>
            <div style="margin-top: 1rem;">
            </div>
        <table style="background-color: #E2EDE3;">
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price (Rs)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><img src="../seller/<?= htmlspecialchars($item['image_url']) ?>" alt="Product Image" width="50"></td>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>


                    <td>
                        <div class="qty-controls" style="display: flex; gap: 12px; justify-content: center;">
                            <form action="update_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item['product_id'] ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit">−</button>
                            </form>
                            <span><?= $item['quantity'] ?></span>
                            <form action="update_cart.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item['product_id'] ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit">+</button>
                            </form>
                        </div>
                    </td>

        <form action="checkout.php" method="post">
                    <?php $sub_total = $item['price'] * $item['quantity']; ?>
                    <td>Rs. <?= number_format($sub_total, 2) ?></td>
                    <td>
                        <a href="remove_cart.php?product_id=<?= $item['product_id']?>" onclick="return confirm('Are you sure you want to remove this item from your cart?');">Remove</a>
                    </td>
                </tr>
                <?php $total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
            <tr> 
                <td colspan="5"><strong>Total</strong></td>
                <td colspan="2"><strong>Rs. <?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>
        <h3 style="text-align:center;">Total: Rs. <?= number_format($total, 2) ?></h3>
                <button type="submit" value="checkout" class="buy-btn" style="margin-left:800px;">Place Order</button>
        </form>
    <?php endif; ?>
    <a href="../landing_page.php" name="action" value="langing_page" class="buy-btn" style="background-color: transparent; color:rgb(67, 72, 67); height:20px;">Continue Shopping</a>
</div>

</body>
</html>