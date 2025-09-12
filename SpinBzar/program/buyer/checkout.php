<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT c.*, p.product_name, p.price, p.image_url 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.product_id 
                        WHERE c.user_id = ?");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    echo "Selected items not found in cart.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SpinBzar</title>
    <link rel="stylesheet" href="../../styles/buyer.css">
    <link rel="stylesheet" href="../../styles/landing_page.css">
    <script>
    function calculateTotal() {
        const city = document.getElementById("shipping_address").value;
        const total = parseFloat(document.getElementById("total").textContent.replace(/,/g, ''));

        let shippingFee = 80; // default
        switch(city.toLowerCase()) {
            case 'kathmandu': shippingFee = 50; break;
            case 'pokhara': shippingFee = 70; break;
            case 'bhaktapur': shippingFee = 60; break;
            case 'lalitpur': shippingFee = 55; break;
        }

        const tax = 0.13 * total;
        const grandTotal = total + tax + shippingFee;

        // Update display
        document.getElementById("shipping_fee").textContent = shippingFee.toFixed(2);
        document.getElementById("tax").textContent = tax.toFixed(2);
        document.getElementById("grand_total").textContent = grandTotal.toFixed(2);

        // Update hidden inputs
        document.getElementById("input_shipping_fee").value = shippingFee.toFixed(2);
        document.getElementById("input_tax").value = tax.toFixed(2);
        document.getElementById("input_grand_total").value = grandTotal.toFixed(2);
    }
</script>

</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="../landing_page.php">Home</a></li>
                <li><a href="../landing_page.php">Shop</a></li>
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
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>

<div class="main-content">
<center><h2>Review Your Order</h2></center>
<form method="post" action="esewa_payment_form.php" id="checkout-form">
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        <?php $total=0;?>   
<?php foreach ($cart_items as $item): ?>
        <tr>
            <td><img src="../seller/<?= htmlspecialchars($item['image_url']) ?>" alt=""></td>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td>Rs. <?= number_format($item['price'], 2) ?></td>
            <td><?= (int)$item['quantity'] ?></td>
            <?php $sub_total = $item['price'] * $item['quantity']; ?>
            <td>Rs. <?= number_format($sub_total, 2) ?></td>
            <input type="hidden" name="sub_total" id="sub_total" value="<?= $sub_total ?>">
            <?php $total += $item['price'] * $item['quantity']; ?>
<?php endforeach; ?>
        </tr>
        <tr>
            <td colspan="5">
                <h1>Shipping Address</h1>
                <select name="shipping_address" id="shipping_address" required onchange="calculateTotal()" style="height:40px; width: 50%;">
                    <option value="">Select-your-city</option>
                    <option value="kathmandu">Kathmandu</option>
                    <option value="pokhara">Pokhara</option>
                    <option value="bhaktapur">Bhaktapur</option>
                    <option value="lalitpur">Lalitpur</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>Total</p>
                <p>Shipping Fee</p>
                <p>Tax (13%)</p>
                <p><strong>Grand Total</strong></p>
            </td>
            <td colspan="2">
                <p>:Rs. <span id="total"><?= number_format($total, 2) ?></span></p>
                <p>: Rs. <span id="shipping_fee">0.00</span></p>
                <p>: Rs. <span id="tax">0.00</span></p>
                <p><strong>: Rs. <span id="grand_total">0.00</span></strong></p>

                <!-- Hidden inputs to pass values -->
                 <input type="hidden" name="total" id="total" value="<?= $total ?>">
                <input type="hidden" name="calculated_shipping_fee" id="input_shipping_fee">
                <input type="hidden" name="calculated_tax" id="input_tax">
                <input type="hidden" name="calculated_grand_total" id="input_grand_total">
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <h3>Payment Method</h3>
                    <label><input type="radio" name="payment_method" value="cash_on_delivery" required> Cash on Delivery</label><br>
                    <label><input type="radio" name="payment_method" value="esewa"> E-sewa</label><br>
                    <label><input type="radio" name="payment_method" value="khalti"> Khalti</label><br><br>
                    
                    <button type="submit" class="buy-btn" id="place-order-btn">Place Order</button>
            </td>
        </tr>
    </table>
</form>
</div>
</body>
</html>
