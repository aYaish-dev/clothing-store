
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$visitorLoggedIn = isset($_SESSION['visitor']);
?>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
  <img src="uploads/logo.png" alt="Logo" width="60" height="60" class="me-2">
  <span>Abdallah Clothing</span>
</a>


    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart 🛒</a></li>

        <?php if ($visitorLoggedIn): ?>
          <li class="nav-item">
            <span class="nav-link text-success">👋 Hello, <?= htmlspecialchars($_SESSION['visitor']['name']); ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning" href="my_account.php">My Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="visitor_login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
