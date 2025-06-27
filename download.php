<?php
session_start();
date_default_timezone_set('Asia/Kolkata'); // ✅ Set IST timezone

include 'config.php';

// Allow both users and admins to download
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $document_id = $_GET['id'];

    // Get the file information from the database
    $sql = "SELECT * FROM documents WHERE id = $document_id";
    $result = mysqli_query($conn, $sql);
    $document = mysqli_fetch_assoc($result);

    if ($document) {
        $filePath = $document['file_path'];

        if (file_exists($filePath)) {
            // ✅ Only log access if it's a normal user
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $access_time = date('Y-m-d H:i:s'); // ⏰ This is now in IST
                $log_sql = "INSERT INTO access_logs (user_id, document_id, accessed_at) 
                            VALUES ('$user_id', '$document_id', '$access_time')";
                mysqli_query($conn, $log_sql);
            }

            // Force download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit();
        } else {
            echo "❌ File not found.";
        }
    } else {
        echo "❌ Document not found in the database.";
    }
} else {
    echo "❌ No file ID provided.";
}
?>
