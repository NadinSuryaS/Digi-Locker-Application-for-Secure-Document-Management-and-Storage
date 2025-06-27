<?php
include 'includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $id;

            // ✅ Insert login time into login_history table
            $log_query = "INSERT INTO login_history (user_id) VALUES (?)";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param("i", $id);
            $log_stmt->execute();
            $log_stmt->close();

            echo "<script>alert('Login Successful! Redirecting to dashboard...'); window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect Password. Try again!'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('No account found with this email. Please register.'); window.location.href='register.php';</script>";
        exit();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiLocker - Login</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Your custom style -->
    <link rel="stylesheet" type="text/css" href="login-style.css">
</head>
<body class="auth-body">
    <div class="auth-container">
        <h2>DigiLocker Login</h2>
        <form action="login.php" method="POST">
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="auth-btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
