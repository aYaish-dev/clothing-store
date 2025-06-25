<?php
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: ''; // خليه فاضي إذا ما حطيت باسورد للـ MySQL
$dbname = getenv('DB_NAME') ?: 'clothing_store';
$admin_email = getenv('ADMIN_EMAIL') ?: 'admin@example.com';

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// فحص الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Automatically add the `status` column if the database hasn't been migrated
$check = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'status'");
if ($check && mysqli_num_rows($check) === 0) {
    mysqli_query(
        $conn,
        "ALTER TABLE orders ADD `status` enum('pending','preparing','completed') NOT NULL DEFAULT 'pending' AFTER total"
    );
}
?>
