<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Helper function
function getReportData($conn, $startDate) {
    $docsQuery = "SELECT COUNT(*) AS total_docs FROM documents WHERE uploaded_at >= '$startDate'";
    $docsResult = mysqli_query($conn, $docsQuery);
    $docCount = mysqli_fetch_assoc($docsResult)['total_docs'];

    $usersQuery = "SELECT COUNT(DISTINCT user_id) AS total_users FROM access_logs WHERE accessed_at >= '$startDate'";
    $usersResult = mysqli_query($conn, $usersQuery);
    $userCount = mysqli_fetch_assoc($usersResult)['total_users'];

    return [
        'documents' => (int)$docCount,
        'active_users' => (int)$userCount
    ];
}

// Get today's date, start of week, start of month
$today = date('Y-m-d 00:00:00');
$startOfWeek = date('Y-m-d 00:00:00', strtotime("monday this week"));
$startOfMonth = date('Y-m-01 00:00:00');

// Prepare response
$response = [
    'today' => getReportData($conn, $today),
    'week' => getReportData($conn, $startOfWeek),
    'month' => getReportData($conn, $startOfMonth)
];

// Send JSON
echo json_encode($response);
?>
