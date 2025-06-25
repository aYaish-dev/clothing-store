<?php
require_once 'session.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $username = trim($_POST['username']);
        $pass = $_POST['password'];

        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=? AND role='visitor'");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $visitor = mysqli_fetch_assoc($result);

            if (password_verify($pass, $visitor['password'])) {
                session_regenerate_id(true);
                $_SESSION['visitor'] = [
                    'id' => $visitor['id'],
                    'name' => $visitor['username']
                ];
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="text-center mb-4">🔑 Login</h3>
                <?php if (isset($error)) echo "<div class='alert alert-danger'>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</div>"; ?>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" required class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="register.php">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
