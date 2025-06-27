<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all uploaded documents with username
$sql = "SELECT documents.id, documents.filename, documents.category, documents.upload_date, users.username 
        FROM documents 
        JOIN users ON documents.user_id = users.id 
        ORDER BY documents.upload_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Uploaded Files</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            margin: 0;
            padding: 30px;
            color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            text-shadow: 0 0 10px #00ffff;
        }

        .nav-buttons {
            text-align: center;
            margin-bottom: 25px;
        }

        .nav-buttons a {
            text-decoration: none;
            margin: 0 10px;
            padding: 12px 22px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .nav-buttons a:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            box-shadow: 0 0 15px #00f2fe;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
        }

        th, td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        th {
            background-color: rgba(0, 0, 0, 0.6);
            color: #00ffff;
            font-size: 16px;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .delete-btn {
            padding: 8px 15px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .delete-btn:hover {
            background: #c0392b;
            box-shadow: 0 0 10px #ff6b6b;
            transform: scale(1.05);
        }

        .icon {
            margin-right: 6px;
        }

        .no-data {
            text-align: center;
            font-weight: bold;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="nav-buttons">
    <a href="admin_dashboard.php"><i class="fas fa-arrow-left icon"></i>Back to Dashboard</a>
    <a href="admin_logout.php"><i class="fas fa-sign-out-alt icon"></i>Logout</a>
</div>

<h2><i class="fas fa-folder-open icon"></i>All Uploaded Documents</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th><i class="fas fa-user"></i> Username</th>
                <th><i class="fas fa-file-alt"></i> Document</th>
                <th><i class="fas fa-tags"></i> Category</th>
                <th><i class="fas fa-calendar-alt"></i> Uploaded At</th>
                <th><i class="fas fa-trash-alt"></i> Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['filename']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['upload_date']) ?></td>
                    <td>
                        <a href="admin_delete.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this document?');">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-data">🚫 No uploaded documents found.</p>
<?php endif; ?>

</body>
</html>
