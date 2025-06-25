<?php
require_once 'session.php';
include 'db.php';

// حماية الصفحة
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// التحقق من وجود ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // حذف المنتج باستخدام prepared statements
    try {
        $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: admin.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "Error deleting product: " . $e->getMessage();
    }
} else {
    echo "No product ID provided.";
}
?>
