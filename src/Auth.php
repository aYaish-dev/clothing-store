<?php
namespace Store;

use PDO;

class Auth
{
    public static function adminLogin(PDO $pdo, string $username, string $password): bool
    {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $username;
            return true;
        }
        return false;
    }

    public static function visitorLogin(PDO $pdo, string $username, string $password): bool
    {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? AND role = 'visitor'");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['visitor'] = ['id' => $row['id'], 'name' => $row['username']];
            return true;
        }
        return false;
    }
}
