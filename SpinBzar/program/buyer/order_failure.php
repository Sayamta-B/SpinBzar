<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Failure</title>
  <style>
    body {
      background-color: #f5f7fa;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
      align-items: center;
      justify-content: center;
    }

    .success-container {
      background-color: #ffffff;
      padding: 40px 60px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 500px;
    }

    .success-container h1 {
      color: #28a745;
      font-size: 2em;
      margin-bottom: 20px;
    }

    .success-container p {
      font-size: 1.1em;
      color: #333;
      margin-bottom: 10px;
    }

    .success-container .details {
      margin-top: 20px;
      text-align: left;
      font-size: 1em;
      color: #555;
    }

    .success-container .details p {
      margin: 6px 0;
    }

    .success-container .details span {
      font-weight: bold;
    }

    .success-container .btn {
      display: inline-block;
      margin-top: 30px;
      padding: 10px 25px;
      background-color:rgb(167, 40, 40);
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .success-container .btn:hover {
      background-color:rgb(136, 33, 33);
    }
  </style>
</head>
<body>
  <div class="success-container">
    <h1>❌ Order Failed!</h1>
    <p>Thank you for your payment. Your order has been placed successfully.</p>

    <a href="cart.php" class="btn">Back to Cart</a>
  </div>
</body>
</html>
