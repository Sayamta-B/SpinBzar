<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_identifier = trim($_POST['user_identifier']);
    $password = $_POST['password'];

    // Prepare statement to fetch user by username or email
    $stmt = $conn->prepare("SELECT user_id, username, email, phone_number, password_hash, user_role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $user_identifier, $user_identifier);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $username, $email, $phone_number, $password_hash, $user_role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $password_hash)) {
            // Set session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email']= $email;
            $_SESSION['phone']= $phone_number;
            $_SESSION['user_role'] = $user_role;
            $_SESSION['logged_in'] = true;

            // Redirect based on role
            if ($user_role === 'admin') {
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: login_landing_page.php");
            }
            exit();
        } else {
            // Redirect with error
            header("Location: login.php?error=invalid_password");
            exit();
        }
    } else {
        // Redirect with error
        header("Location: login.php?error=user_not_found");
        exit();
    }
    $stmt->close();
} else {
    header("Location: login.php?error=invalid_request");
    exit();
}
?>
