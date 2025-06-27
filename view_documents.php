<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's documents
$query = "SELECT * FROM documents WHERE user_id = $user_id ORDER BY uploaded_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Uploaded Documents</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e0f7fa;
            padding: 30px;
        }
        h2 {
            color: #00796b;
            margin-bottom: 20px;
        }
        .doc-item {
            background: #ffffff;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-left: 5px solid #00796b;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 6px;
        }
        .doc-item strong {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        .doc-actions a {
            margin-right: 15px;
            text-decoration: none;
            color: #00796b;
            font-weight: bold;
        }
        .doc-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2><i class="fas fa-folder-open"></i> My Uploaded Documents</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($doc = mysqli_fetch_assoc($result)): ?>
        <div class="doc-item">
            <strong><?= htmlspecialchars($doc['filename']) ?></strong>
            <div class="doc-actions">
                <!-- 🔐 Use verify_password.php instead of direct view -->
                <a href="verify_password.php?id=<?= $doc['id'] ?>"><i class="fas fa-eye"></i> View</a>
                <a href="delete.php?id=<?= $doc['id'] ?>" onclick="return confirm('Are you sure to delete this file?');"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No documents uploaded yet.</p>
<?php endif; ?>

</body>
</html>
