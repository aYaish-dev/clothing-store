<?php
declare(strict_types=1);
namespace Store;

use PDO;
use PHPMailer\PHPMailer\PHPMailer;

class Checkout
{
    /**
     * @param array $cart Each item: ['id' => int, 'size' => string, 'qty' => int, 'price' => float]
     * @return int Order ID
     */
    public static function placeOrder(PDO $pdo, int $userId, string $fullname, string $phone, string $address, array $cart): int
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
        $adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
        $mailer = new PHPMailer();
        $mailer->setFrom('no-reply@localhost');
        $mailer->addAddress($adminEmail);
        $mailer->Subject = 'New Order';
        $mailer->Body = "New order #$orderId placed by $fullname. Total: $$total";
        @$mailer->send();
        return $orderId;
    }
}
