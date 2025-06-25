<?php
declare(strict_types=1);
namespace Store;

use PDO;

class Checkout
{
    /**
     * @param array $cart Each item: ['id' => int, 'size' => string, 'qty' => int, 'price' => float, 'discount' => float]
     * @param float $couponPercent Percent off to apply to total
     * @return int Order ID
     */
    public static function placeOrder(PDO $pdo, int $userId, string $fullname, string $phone, string $address, array $cart, float $couponPercent = 0.0): int
    {
        if (empty($cart)) {
            throw new \InvalidArgumentException('Cart is empty');
        }

        $total = 0.0;
        foreach ($cart as &$item) {
            $price = $item['price'];
            $itemDiscount = isset($item['discount']) ? (float)$item['discount'] : 0.0;
            if ($itemDiscount > 0) {
                $itemDiscount = max(0.0, min(100.0, $itemDiscount));
                $price -= $price * ($itemDiscount / 100);
            }
            $item['final_price'] = $price; // store for order_items
            $total += $price * $item['qty'];
            $stock = Database::getStock($pdo, $item['id'], $item['size']);
            if ($stock < $item['qty']) {
                throw new \RuntimeException('Not enough stock for product ' . $item['id']);
            }
        }

        if ($couponPercent > 0) {
            $couponPercent = max(0.0, min(100.0, $couponPercent));
            $total -= $total * ($couponPercent / 100);
        }

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, username, phone, address, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$userId, $fullname, $phone, $address, $total]);
        $orderId = (int)$pdo->lastInsertId();

        foreach ($cart as $item) {
            Database::decreaseStock($pdo, $item['id'], $item['size'], $item['qty']);
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$orderId, $item['id'], $item['size'], $item['qty'], $item['final_price']]);
        }
        $pdo->commit();

        return $orderId;
    }
}
