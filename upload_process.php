<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $filename = $_FILES['document']['name'];
    $tmp_name = $_FILES['document']['tmp_name'];
    $upload_dir = 'uploads/';
    $file_path = $upload_dir . basename($filename);

    if (move_uploaded_file($tmp_name, $file_path)) {
        $sql = "INSERT INTO documents (user_id, filename, file_path, category) 
                VALUES ('$user_id', '$filename', '$file_path', '$category')";
        mysqli_query($conn, $sql);
        header("Location: dashboard.php?msg=success");
        exit();
    } else {
        echo "File upload failed!";
    }
} else {
    echo "Invalid request!";
}
?>
