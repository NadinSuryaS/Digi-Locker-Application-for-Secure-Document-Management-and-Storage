<?php
session_start();
include 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// When the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the form
    $document_name = $_POST['document_name'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];
    $doc_password = $_POST['doc_password'] ?? '';  // Optional password

    // Check if a file is uploaded
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $filename = basename($_FILES['document']['name']);
        $upload_dir = "uploads/";  // Folder to save the file
        $new_filename = time() . "_" . $filename;  // Make the file name unique
        $target_file = $upload_dir . $new_filename;
        $file_size = $_FILES['document']['size'];  // Get the file size

        // Generate a unique share token
        $share_token = bin2hex(random_bytes(10));

        // Hash the password if it is set
        $hashed_password = $doc_password !== '' ? password_hash($doc_password, PASSWORD_DEFAULT) : null;

        // Move the file to the target folder
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_file)) {
            // Insert the document data into the database
            $sql = "INSERT INTO documents 
                    (user_id, document_name, filename, file_path, category, upload_date, file_size, share_token, doc_password) 
                    VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?)";

            // Prepare the SQL statement
            $stmt = mysqli_prepare($conn, $sql);

            // Bind the parameters to the SQL query (Notice no 's' for upload_date as it is handled by NOW())
            mysqli_stmt_bind_param($stmt, "issssiss", 
                $user_id, 
                $document_name, 
                $filename, 
                $target_file, 
                $category, 
                $file_size, 
                $share_token, 
                $hashed_password
            );

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                $message = "✅ Document uploaded successfully!";
            } else {
                $message = "❌ Failed to save in DB.";
            }
        } else {
            $message = "❌ Error uploading the file.";
        }
    } else {
        $message = "❌ Please choose a valid file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Document</title>
    <link rel="stylesheet" href="upload_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="upload-body">
    <div class="upload-container">
        <h2 class="glow-title"><i class="fas fa-cloud-upload-alt"></i> Upload Your Document</h2>

        <?php if ($message): ?>
            <p style="color: #00ffcc; font-weight: bold;"><?= $message ?></p>
        <?php endif; ?>

        <form class="upload-form" action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="document_name" placeholder="📄 Enter Document Name" required><br>
            <select name="category" required>
                <option value="">📁 Select Category</option>
                <option value="Aadhaar">Aadhaar</option>
                <option value="Education">Education</option>
                <option value="Legal Document">Legal Document</option>
            </select><br>
            <input type="file" name="document" required><br>

            <!-- Password field (optional) -->
            <input type="password" name="doc_password" placeholder="🔐 Set Password (optional)"><br>

            <button type="submit" class="upload-btn"><i class="fas fa-upload"></i> Upload</button>
        </form>

        <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</body>
</html>