<?php
session_start();
include 'includes/db.php'; // ✅ Make sure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email_db, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_email'] = $email_db;
            header("Location: admin_dashboard.php"); // ✅ Redirect if successful
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.location.href='admin_login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
