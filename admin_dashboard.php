<?php  
session_start();
include 'config.php';

date_default_timezone_set('Asia/Kolkata'); // ✅ Set timezone to IST

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch access logs
$logsQuery = "SELECT access_logs.*, users.username, documents.filename 
              FROM access_logs 
              LEFT JOIN users ON access_logs.user_id = users.id
              LEFT JOIN documents ON access_logs.document_id = documents.id
              ORDER BY access_logs.accessed_at DESC
              LIMIT 10";
$logsResult = mysqli_query($conn, $logsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DigiLocker Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #0f2027, #00c6ff);
            background-size: 800% 800%;
            animation: superGradient 10s ease-in-out infinite;
            color: #ecf0f1;
        }

        @keyframes superGradient {
            0% { background-position: 0% 50%; }
            25% { background-position: 50% 100%; }
            50% { background-position: 100% 50%; }
            75% { background-position: 50% 0%; }
            100% { background-position: 0% 50%; }
        }

        .sidebar {
            width: 260px;
            background: rgba(0, 0, 0, 0.85);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 20px rgba(0, 255, 255, 0.3);
            backdrop-filter: blur(12px);
        }

        .sidebar h2 {
            font-size: 26px;
            text-align: center;
            margin-bottom: 40px;
            color: #00f2fe;
            text-shadow: 0 0 10px #00f2fe, 0 0 20px #00f2fe;
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px #00f2fe; }
            to   { text-shadow: 0 0 30px #00f2fe, 0 0 40px #00f2fe; }
        }

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            margin-bottom: 15px;
            font-size: 16px;
            padding: 12px 18px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            background: #00f2fe;
            color: #000;
            font-weight: bold;
            box-shadow: 0 0 15px #00f2fe;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: 30px;
            margin-bottom: 30px;
            color: #00f2fe;
            text-shadow: 0 0 10px #00f2fe;
        }

        .logs {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .log-item {
            background: rgba(255, 255, 255, 0.07);
            border-left: 5px solid #00f2fe;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,255,255,0.25);
            transition: transform 0.3s ease;
        }

        .log-item:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px rgba(0,255,255,0.4);
        }

        .no-logs {
            background: #e74c3c;
            color: white;
            padding: 20px;
            border-radius: 12px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.4);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>DigiLocker Admin</h2>
    <a href="admin_reports.php">📊 View Admin Reports</a>
    <a href="admin_login_history.php">🕓 View User Login History</a>
    <a href="admin_view_files.php" class="btn">📁 View Uploaded Files</a>
    <a href="admin_logout.php">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <h1>📋 Recent File Access Logs</h1>
    <div class="logs">
        <?php if (mysqli_num_rows($logsResult) > 0): ?>
            <?php while ($log = mysqli_fetch_assoc($logsResult)): ?>
                <div class="log-item">
                    User "<strong><?= htmlspecialchars($log['username']) ?></strong>" accessed file 
                    "<strong><?= htmlspecialchars($log['filename']) ?></strong>" 
                    at <?= date("Y-m-d h:i A", strtotime($log['accessed_at'])) ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-logs">No access logs found.</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
