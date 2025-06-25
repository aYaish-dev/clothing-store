<?php
namespace Store;

use PDO;

class Cart
{
    public static function updateQuantity(PDO $pdo, string $key, int $quantity, string $csrfToken): array
    {
        if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
            return ['success' => false, 'message' => 'Invalid CSRF token'];
        }
        if ($quantity < 1 || $quantity > 1000) {
            return ['success' => false, 'message' => 'Invalid quantity'];
        }
        if (!isset($_SESSION['cart'][$key])) {
            return ['success' => false, 'message' => 'Item not found in cart'];
        }
        $item = $_SESSION['cart'][$key];
        $pid = (int)$item['product']['id'];
        $size = $item['size'];
        $stock = Database::getStock($pdo, $pid, $size);
        if ($quantity > $stock) {
            return ['success' => false, 'message' => "❌ Only $stock items in stock for size $size"];
        }
        $_SESSION['cart'][$key]['quantity'] = $quantity;
        $subtotal = $item['product']['price'] * $quantity;
        $total = 0;
        foreach ($_SESSION['cart'] as $c) {
            $total += $c['product']['price'] * $c['quantity'];
        }
        return [
            'success' => true,
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2)
        ];
    }
}
