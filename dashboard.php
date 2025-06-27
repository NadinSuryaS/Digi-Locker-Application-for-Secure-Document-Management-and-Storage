<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$success = $_GET['success'] ?? '';

$sql = "SELECT * FROM documents WHERE user_id = $user_id";

if ($search != '') {
    $sql .= " AND filename LIKE '%$search%'";
}
if ($category != '') {
    $sql .= " AND category = '$category'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">
    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>

    <div class="dashboard-container">
        <h1 class="welcome-heading">Welcome, <?= htmlspecialchars($username) ?></h1>
        <p class="subheading">Your Secure Document Storage</p>

        <?php if ($success == '1'): ?>
            <div class="success-message"><i class="fas fa-check-circle"></i> Document uploaded successfully!</div>
        <?php endif; ?>

        <form action="dashboard.php" method="GET" class="search-filter">
            <input type="text" name="search" placeholder="🔍 Search by document name..." class="search-input">
            <select name="category" class="category-dropdown">
                <option value="">All Categories</option>
                <option value="Aadhaar">Aadhaar</option>
                <option value="Education">Education</option>
                <option value="Legal Document">Legal Document</option>
            </select>
            <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
        </form>

        <a href="upload.php" class="upload-btn"><i class="fas fa-upload"></i> Upload New Document</a>

        <h2 class="document-heading">Your Uploaded Documents</h2>
        <table class="documents-table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Category</th>
                    <th>Uploaded At</th>
                    <th>View</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['filename']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['upload_date']) ?></td>
                    <td>
                        <!-- View Button goes to verify_password.php -->
                        <a href="verify_password.php?id=<?= $row['id'] ?>" class="action-btn view" style="background-color: #6c5ce7;">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                    <td>
                        <a href="download.php?id=<?= $row['id'] ?>" class="action-btn download"><i class="fas fa-download"></i> Download</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this file?');" class="action-btn delete"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
