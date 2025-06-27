<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$doc_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$error = "";

// Fetch the document
$sql = "SELECT * FROM documents WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $doc_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$doc = mysqli_fetch_assoc($result);

if (!$doc) {
    echo "❌ Document not found or you don't have access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'];

    if ($doc['doc_password'] === null || password_verify($input_password, $doc['doc_password'])) {
        // Show the document
        header("Location: " . $doc['file_path']);
        exit();
    } else {
        $error = "❌ Incorrect password. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: url('assets/images/password_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }

        .password-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 40px;
            max-width: 500px;
            margin: 10% auto;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.6);
            text-align: center;
        }

        .password-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff;
        }

        .password-container input[type="password"] {
            padding: 12px;
            width: 80%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 20px;
            outline: none;
        }

        .password-container button {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background: linear-gradient(to right, #00bcd4, #2196f3);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .password-container button:hover {
            background: linear-gradient(to right, #2196f3, #00bcd4);
            box-shadow: 0 0 15px #00ffff;
        }

        .error-message {
            color: #ff4d4d;
            font-weight: bold;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            color: white;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 0 15px #ff4b2b;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: linear-gradient(to right, #ff4b2b, #ff416c);
            box-shadow: 0 0 20px #ff4b2b;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <h2><i class="fas fa-lock"></i>  Password Required</h2>
        <p>This document is protected. Please enter the password:</p>

        <?php if ($error): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="password" placeholder="🔑 Enter password" required><br>
            <button type="submit"><i class="fas fa-eye"></i> View Document</button>
        </form>

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>
