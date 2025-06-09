<?php
session_start();
include 'db.php';

// حماية الصفحة
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// التحقق من وجود ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // حذف المنتج
    $delete = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $delete)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
} else {
    echo "No product ID provided.";
}
?>
