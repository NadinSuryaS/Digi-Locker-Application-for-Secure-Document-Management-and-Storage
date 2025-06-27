<?php
include 'includes/db.php';

$sql = "SELECT id, file_path FROM documents WHERE file_size IS NULL OR file_size = 0";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $file_path = $row['file_path'];

    if (file_exists($file_path)) {
        $size = filesize($file_path); // in bytes
        $update = "UPDATE documents SET file_size = $size WHERE id = $id";
        mysqli_query($conn, $update);
    }
}

echo "✅ File sizes updated!";
?>
