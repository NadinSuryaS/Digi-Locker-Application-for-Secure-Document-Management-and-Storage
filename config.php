<?php
// Database connection settings
$host = 'localhost';
$db = 'digi_locker'; // make sure this database is created in phpMyAdmin
$user = 'root';
$pass = ''; // no password by default in XAMPP

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
