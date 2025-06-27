<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Force browser to not cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Reports - Digi Locker</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
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

        .chart-container {
            width: 95%;
            max-width: 900px;
            margin: 40px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        canvas {
            margin: 30px auto;
            display: block;
            max-height: 420px !important;
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 18px;
        }

        th, td {
            padding: 10px 15px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        h3 {
            margin-top: 0;
            color: #34495e;
        }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<h2>📊 Admin Reports & Analytics</h2>

<!-- Most Active Users -->
<div class="chart-container">
    <h3>👤 Most Active Users</h3>
    <canvas id="userChart"></canvas>
    <table>
        <tr><th>Username</th><th>Documents Uploaded</th></tr>
        <?php
        $usernames = [];
        $doc_counts = [];

        $user_query = "SELECT users.username, COUNT(documents.id) as document_count 
                       FROM documents 
                       JOIN users ON documents.user_id = users.id 
                       GROUP BY users.id 
                       ORDER BY document_count DESC LIMIT 5";
        $user_result = mysqli_query($conn, $user_query);
        while ($row = mysqli_fetch_assoc($user_result)) {
            echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . $row['document_count'] . "</td></tr>";
            $usernames[] = $row['username'];
            $doc_counts[] = $row['document_count'];
        }
        ?>
    </table>
</div>

<!-- Documents by Category -->
<div class="chart-container">
    <h3>📂 Documents by Category</h3>
    <canvas id="categoryBarChart"></canvas>
    <canvas id="categoryPieChart"></canvas>
    <table>
        <tr><th>Category</th><th>Documents</th></tr>
        <?php
        $cat_labels = [];
        $cat_data = [];

        $cat_query = "SELECT category, COUNT(*) as count FROM documents GROUP BY category";
        $cat_result = mysqli_query($conn, $cat_query);
        while ($row = mysqli_fetch_assoc($cat_result)) {
            echo "<tr><td>" . htmlspecialchars($row['category']) . "</td><td>" . $row['count'] . "</td></tr>";
            $cat_labels[] = $row['category'];
            $cat_data[] = $row['count'];
        }
        ?>
    </table>
</div>

<!-- Daily Uploads -->
<div class="chart-container">
    <h3>📅 Daily Uploads</h3>
    <canvas id="dailyChart"></canvas>
    <table>
        <tr><th>Date</th><th>Uploads</th></tr>
        <?php
        $daily_labels = [];
        $daily_data = [];

        $daily_query = "SELECT DATE(upload_date) as day, COUNT(*) as count 
                        FROM documents GROUP BY DATE(upload_date)";
        $daily_result = mysqli_query($conn, $daily_query);
        while ($row = mysqli_fetch_assoc($daily_result)) {
            echo "<tr><td>" . $row['day'] . "</td><td>" . $row['count'] . "</td></tr>";
            $daily_labels[] = $row['day'];
            $daily_data[] = $row['count'];
        }
        ?>
    </table>
</div>

<script>
    // Chart Colors
    const pieColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#1ABC9C'];

    // Most Active Users Bar Chart
    new Chart(document.getElementById('userChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($usernames) ?>,
            datasets: [{
                label: 'Documents Uploaded',
                data: <?= json_encode($doc_counts) ?>,
                backgroundColor: '#5DADE2',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Documents by Category Bar Chart
    new Chart(document.getElementById('categoryBarChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($cat_labels) ?>,
            datasets: [{
                label: 'Documents',
                data: <?= json_encode($cat_data) ?>,
                backgroundColor: '#F7DC6F',
                borderColor: '#F1C40F',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Documents by Category Pie Chart
    new Chart(document.getElementById('categoryPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($cat_labels) ?>,
            datasets: [{
                data: <?= json_encode($cat_data) ?>,
                backgroundColor: pieColors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Daily Uploads Line Chart
    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($daily_labels) ?>,
            datasets: [{
                label: 'Uploads per Day',
                data: <?= json_encode($daily_data) ?>,
                borderColor: '#2ECC71',
                backgroundColor: 'rgba(46, 204, 113, 0.3)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

</body>
</html>
