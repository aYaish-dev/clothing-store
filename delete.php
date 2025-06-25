<?php
require_once 'session.php';
include 'db.php';

// حماية الصفحة
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// التحقق من الطلب وطابع CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['csrf_token'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('HTTP/1.1 400 Bad Request');
        exit('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];

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
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Invalid request.';
}
?>
