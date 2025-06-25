<?php
require_once 'session.php';
include 'db.php';
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = "Please fill in all fields.";
    } else {
        // Check if the username already exists
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username=?");
        if ($check) {
            mysqli_stmt_bind_param($check, "s", $username);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) > 0) {
                $error = "Username already exists.";
            } else {
                // Hash the password and insert the new user
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $insert = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, 'visitor')");
                if ($insert) {
                    mysqli_stmt_bind_param($insert, "ss", $username, $hashed);
                    if (mysqli_stmt_execute($insert)) {
                        $success = "Account created! You can login now.";
                    } else {
                        $error = "Error creating account.";
                    }
                    mysqli_stmt_close($insert);
                } else {
                    $error = "Error creating account.";
                }
            }
            mysqli_stmt_close($check);
        } else {
            $error = "Error creating account.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">🆕 Create Account</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="visitor_login.php">Already have an account?</a>
        </div>
    </div>
</div>

</body>
</html>
