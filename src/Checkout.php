<?php
declare(strict_types=1);
namespace Store;

use PDO;

class Checkout
{
    /**
     * @param array $cart Each item: ['id' => int, 'size' => string, 'qty' => int, 'price' => float]
     * @param float $discountPercent Percent off to apply to total
     * @return int Order ID
     */
    public static function placeOrder(PDO $pdo, int $userId, string $fullname, string $phone, string $address, array $cart, float $discountPercent = 0.0): int
    {
        if (empty($cart)) {
            throw new \InvalidArgumentException('Cart is empty');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
            $stock = Database::getStock($pdo, $item['id'], $item['size']);
            if ($stock < $item['qty']) {
                throw new \RuntimeException('Not enough stock for product ' . $item['id']);
            }
        }

        if ($discountPercent > 0) {
            $discountPercent = max(0.0, min(100.0, $discountPercent));
            $total -= $total * ($discountPercent / 100);
        }

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, username, phone, address, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$userId, $fullname, $phone, $address, $total]);
        $orderId = (int)$pdo->lastInsertId();

        foreach ($cart as $item) {
            Database::decreaseStock($pdo, $item['id'], $item['size'], $item['qty']);
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$orderId, $item['id'], $item['size'], $item['qty'], $item['price']]);
        }
        $pdo->commit();

        return $orderId;
    }
}
