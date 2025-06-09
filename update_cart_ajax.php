<?php
session_start();
include 'db.php';

if (!isset($_POST['key'], $_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$key = $_POST['key'];
$newQty = (int) $_POST['quantity'];

if (!isset($_SESSION['cart'][$key])) {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    exit;
}

$item = $_SESSION['cart'][$key];
$pid = (int) $item['product']['id'];
$size = mysqli_real_escape_string($conn, $item['size']);

// جلب الكمية المتاحة من قاعدة البيانات
$stock_result = mysqli_query($conn, "SELECT quantity FROM product_sizes WHERE product_id = $pid AND size = '$size'");
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
