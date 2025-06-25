<?php
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: ''; // خليه فاضي إذا ما حطيت باسورد للـ MySQL
$dbname = getenv('DB_NAME') ?: 'clothing_store';

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// فحص الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
