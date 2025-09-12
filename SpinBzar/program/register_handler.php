<?php
require 'db.php'; // make sure this file connects to your DB

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Gather input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone_number"]);
    $address = trim($_POST["address"]);
    $role = trim($_POST["role"]);

    // Check if any admin exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_role = 'admin'");
    $stmt->execute();
    $stmt->bind_result($admin_count);
    $stmt->fetch();
    $stmt->close();


    // Check for duplicate username/email
    $check = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        die("Username or email already taken.");
    }


    // Insert user
    $insert = $conn->prepare("INSERT INTO users (username, email, password_hash, full_name, phone_number, address, user_role, created_at, updated_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $insert->bind_param("sssssss", $username, $email, $password, $full_name, $phone, $address, $role);

if ($insert->execute()) {
    echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
} else {
    echo "Error: Could not register.";
}
}
?>
