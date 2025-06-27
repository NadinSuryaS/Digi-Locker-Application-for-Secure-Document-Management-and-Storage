<?php
if (!isset($_GET['file'])) {
    echo "No file provided.";
    exit;
}

$filename = $_GET['file'];
$baseURL = "http://localhost/digi_locker/shared_view.php?file=";
$shareLink = $baseURL . urlencode($filename);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Share Link</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ecf0f1;
            text-align: center;
            padding-top: 100px;
        }
        h2 {
            color: #2c3e50;
        }
        input {
            width: 70%;
            padding: 12px;
            font-size: 16px;
            margin-top: 20px;
        }
        .copy-btn {
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        .copy-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h2>🔗 Shareable Document Link</h2>
    <input type="text" id="link" value="<?= $shareLink ?>" readonly>
    <br>
    <button class="copy-btn" onclick="copyLink()">Copy Link</button>

    <script>
        function copyLink() {
            var copyText = document.getElementById("link");
            copyText.select();
            document.execCommand("copy");
            alert("Copied: " + copyText.value);
        }
    </script>

</body>
</html>
