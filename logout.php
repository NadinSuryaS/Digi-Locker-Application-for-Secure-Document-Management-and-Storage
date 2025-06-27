<?php
session_start();
session_destroy();
header("Location: index.php"); // ✅ Redirect directly to index page
exit();
?>
