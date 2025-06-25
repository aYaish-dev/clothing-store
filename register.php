<?php
session_start();
include 'db.php';
include 'csrf.php';
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['password'];

    if ($username === '' || strlen($username) > 50) {
        $error = "Invalid username.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists.";
        } else {
            $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed', 'visitor')");
            if ($insert) {
                $success = "Account created! You can login now.";
            } else {
                $error = "Error creating account.";
            }
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
            <input type="hidden" name="token" value="<?php echo get_csrf_token(); ?>">
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
