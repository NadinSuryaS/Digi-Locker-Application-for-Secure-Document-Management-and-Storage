<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Access - Digi Locker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="admin_style.css">
</head>
<body class="admin-auth-body">

    <div class="admin-auth-container">
        <h2><i class="fas fa-user-shield"></i> Admin Access</h2>
        <a href="admin_dashboard.php" class="admin-login-btn">
            <i class="fas fa-lock"></i> Go to Admin Dashboard
        </a>
    </div>

</body>
</html>
