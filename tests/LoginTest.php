<?php
namespace Store\Tests;

use PHPUnit\Framework\TestCase;
use Store\Auth;
use PDO;

class LoginTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT, password TEXT, role TEXT)");
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?), (?, ?, ?)");
        $stmt->execute([
            'admin', password_hash('adminpass', PASSWORD_DEFAULT), 'admin',
            'visitor', password_hash('visitorpass', PASSWORD_DEFAULT), 'visitor'
        ]);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    public function testAdminLoginSuccess(): void
    {
        $this->assertTrue(Auth::adminLogin($this->pdo, 'admin', 'adminpass'));
        $this->assertSame('admin', $_SESSION['admin']);
    }

    public function testAdminLoginFailure(): void
    {
        $this->assertFalse(Auth::adminLogin($this->pdo, 'admin', 'wrong'));
        $this->assertArrayNotHasKey('admin', $_SESSION);
    }

    public function testVisitorLoginSuccess(): void
    {
        $this->assertTrue(Auth::visitorLogin($this->pdo, 'visitor', 'visitorpass'));
        $this->assertSame('visitor', $_SESSION['visitor']['name']);
    }

    public function testVisitorLoginFailure(): void
    {
        $this->assertFalse(Auth::visitorLogin($this->pdo, 'visitor', 'bad'));
        $this->assertArrayNotHasKey('visitor', $_SESSION);
    }
}
