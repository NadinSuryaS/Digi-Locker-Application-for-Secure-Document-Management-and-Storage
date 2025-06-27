<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Digi Locker</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- for icons -->
</head>
<body class="admin-login-body">
    <div class="admin-login-container">
        <h2><i class="fas fa-user-shield"></i> Admin Login</h2>
        <form action="admin_login_process.php" method="POST">
            <input type="email" name="email" placeholder="Enter admin email" required>
            <input type="password" name="password" placeholder="Enter admin password" required>
            <button type="submit" class="admin-login-btn"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>
</body>
</html>
