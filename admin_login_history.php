<?php
include 'includes/db.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM login_history WHERE id = $id");
    header("Location: admin_login_history.php");
    exit;
}

// Fetch login history with username using JOIN
$query = "
    SELECT login_history.id, users.username, login_history.login_time 
    FROM login_history 
    JOIN users ON login_history.user_id = users.id 
    ORDER BY login_history.login_time DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-btn {
            display: block;
            width: 200px;
            margin: 0 auto 30px;
            padding: 12px 20px;
            text-align: center;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 30px;
            box-shadow: 0 0 15px rgba(106, 17, 203, 0.8);
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            box-shadow: 0 0 25px rgba(106, 17, 203, 1);
            transform: scale(1.05);
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<h1>🕒 DigiLocker Admin - Login History</h1>

<a href="admin_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<table>
    <tr>
        <th>Username</th>
        <th>Login Time</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['login_time']); ?></td>
            <td>
                <a href="admin_login_history.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this login history?')">
                    <button class="delete-btn">Delete</button>
                </a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
