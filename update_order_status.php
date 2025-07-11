<?php
require_once 'session.php';
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
$token = $_POST['csrf_token'] ?? '';
$allowed = ['pending','preparing','completed'];
if ($id && in_array($status, $allowed, true) && $token === $_SESSION['csrf_token']) {
    $stmt = mysqli_prepare($conn, "UPDATE orders SET status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    mysqli_stmt_execute($stmt);
}

header('Location: orders.php');
exit();
