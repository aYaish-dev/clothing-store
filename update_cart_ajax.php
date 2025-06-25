<?php
header('Content-Type: application/json');
require_once 'session.php';
include 'db.php';

if (!isset($_POST['key'], $_POST['quantity'], $_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}
$csrf = $_POST['csrf_token'];
if ($csrf !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$key = $_POST['key'];
$newQty = (int) $_POST['quantity'];
if ($newQty < 1 || $newQty > 1000) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit;
}

if (!isset($_SESSION['cart'][$key])) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

$item = $_SESSION['cart'][$key];
$pid = (int) $item['product']['id'];
$size = $item['size'];

// جلب الكمية المتاحة من قاعدة البيانات باستخدام استعلام محضر
$stock_stmt = mysqli_prepare($conn, "SELECT quantity FROM product_sizes WHERE product_id = ? AND size = ?");
mysqli_stmt_bind_param($stock_stmt, "is", $pid, $size);
mysqli_stmt_execute($stock_stmt);
$stock_result = mysqli_stmt_get_result($stock_stmt);
$stock_row = mysqli_fetch_assoc($stock_result);
$stock = $stock_row['quantity'] ?? 0;

if ($newQty > $stock) {
    echo json_encode(['success' => false, 'message' => "❌ Only $stock items in stock for size $size"]);
    exit;
}

// تحديث الكمية في السلة
$_SESSION['cart'][$key]['quantity'] = $newQty;

// حساب الـ subtotal و total
$subtotal = $item['product']['price'] * $newQty;
$total = 0;
foreach ($_SESSION['cart'] as $c) {
    $total += $c['product']['price'] * $c['quantity'];
}

// إرسال الرد
echo json_encode([
    'success' => true,
    'subtotal' => number_format($subtotal, 2),
    'total' => number_format($total, 2)
]);
exit;
