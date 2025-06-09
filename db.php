<?php
$servername = "localhost";
$username = "root";
$password = ""; // خليه فاضي إذا ما حطيت باسورد للـ MySQL
$dbname = "clothing_store";

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// فحص الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
