<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../styles/register.css">
  <title>SpinBzar</title>
  <script>
    function check() {
      let isValid = true;

      const full_name = document.getElementById("full_name").value.trim();
      const regExpName = /^[a-zA-Z\s]+$/;
      const username = document.getElementById("username").value.trim();
      const regExpUser = /^[a-zA-Z0-9_\.]{3,20}$/;
      const email = document.getElementById("email").value.trim();
      const regExpEmail = /^[a-zA-Z]+[a-zA-Z0-9_!#$%&’*+/=?^`{|}~-]+@[a-zA-Z]+\.[a-zA-Z]{2,}$/;
      const phone_number = document.getElementById("phone_number").value.trim();
      const regExpPhone = /^\+?(977)?98[0-9]{8}$/;

      document.getElementById("fullnameErr").style.display = "none";
      document.getElementById("usernameErr").style.display = "none";
      document.getElementById("emailErr").style.display = "none";
      document.getElementById("phoneErr").style.display = "none";

      if (!regExpUser.test(username)) {
        document.getElementById("usernameErr").innerHTML = "!!!Please Enter valid alphanumeric Username(_ and . are allowed)!";
        document.getElementById("usernameErr").style.display = "block";
        isValid = false;
      }

      if (!regExpName.test(full_name)) {
        document.getElementById("fullnameErr").innerHTML = "!!!Please enter a valid full name!!!";
        document.getElementById("fullnameErr").style.display = "block";
        isValid = false;
      }

      if (!regExpEmail.test(email)) {
        document.getElementById("emailErr").innerHTML = "!!!Invalid email format!!!";
        document.getElementById("emailErr").style.display = "block";
        isValid = false;
      }

      if (!regExpPhone.test(phone_number)) {
        document.getElementById("phoneErr").innerHTML = "!!!Invalid phone number format!!!";
        document.getElementById("phoneErr").style.display = "block";
        isValid = false;
      }

      return isValid;
    }
  </script>
</head>
<body>
<div class="container">
  <div class="login1">
    <h2>Register</h2>
    <form action="register_handler.php" method="POST" onsubmit="return check()">
      <input type="text" name="username" id="username" placeholder="Username" required><br>
      <div id="usernameErr" style="color:red; display:none;"></div>

      <input type="email" name="email" id="email" placeholder="Email" required><br>
      <div id="emailErr" style="color:red; display:none;"></div>

      <input type="password" name="password" id="password" placeholder="Password" required><br>

      <input type="text" name="full_name" id="full_name" placeholder="Full Name" required><br>
      <div id="fullnameErr" style="color:red; display:none;"></div>

      <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number"><br>
      <div id="phoneErr" style="color:red; display:none;"></div>

      
        <select name="address" id="address" required>
            <option value="">-- Select your city --</option>
            <option value="kathmandu">Kathmandu</option>
            <option value="pokhara">Pokhara</option>
            <option value="bhaktapur">Bhaktapur</option>
            <option value="lalitpur">Lalitpur</option>
        </select><br/>

      <select name="role" id="role">
        <option value="consumer" selected>Consumer</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit" class="fod">Register</button><br/>
      <a href="landing_page.php" class="fod" style="text-decoration:none; display:inline-block; margin-top:5px;">Cancel</a><br/>

      <div class="link-buttons">
        <a href="login.php" class="link-btn">Have an account? Login</a>
      </div>
    </form>
  </div>
</div>
<script src="../styles/register.js"></script>
</body>
</html>
