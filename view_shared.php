<?php
// Step 1: Connect to DB
include('includes/db.php');

// Step 2: Check if 'id' is provided in URL
if (!isset($_GET['id'])) {
    echo "Invalid link. No document ID found.";
    exit();
}

$doc_id = intval($_GET['id']); // Make sure it's a number

// Step 3: Fetch document info from database
$sql = "SELECT * FROM documents WHERE id = $doc_id";
$result = mysqli_query($conn, $sql);

// Step 4: If found, show download link
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $file_path = "uploads/" . $row['file_name'];

    // Step 5: Check if file exists
    if (file_exists($file_path)) {
        // Show the file download/view link
        echo "<h2>Document: " . htmlspecialchars($row['file_name']) . "</h2>";
        echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
        echo "<p>Uploaded At: " . htmlspecialchars($row['upload_time']) . "</p>";
        echo "<a href='" . $file_path . "' target='_blank'>📄 Click here to view/download</a>";
    } else {
        echo "File does not exist on the server.";
    }
} else {
    echo "Document not found.";
}
?>
