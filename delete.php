<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch file path
    $query = "SELECT file_path FROM documents WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $file_path = $row['file_path'];

        // Delete file from folder
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete from database
        $delete = "DELETE FROM documents WHERE id = $id";
        if (mysqli_query($conn, $delete)) {
            echo "<script>
                    alert('File deleted successfully');
                    window.location.href='dashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Failed to delete file from database.');
                    window.location.href='dashboard.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('File not found.');
                window.location.href='dashboard.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.location.href='dashboard.php';
          </script>";
}
?>
