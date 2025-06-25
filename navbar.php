
<?php
require_once 'session.php';
$visitorLoggedIn = isset($_SESSION['visitor']);
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
      <img src="uploads/logo.png" alt="Logo" width="50" height="50" class="me-2">
      <span>Abdallah Clothing</span>
    </a>

    <form class="d-none d-lg-flex mx-auto" action="search.php" method="GET">
      <input class="form-control search-input me-2" type="search" name="q" placeholder="Search">
      <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <form class="d-lg-none d-flex my-3" action="search.php" method="GET">
        <input class="form-control me-2" type="search" name="q" placeholder="Search">
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
      </form>
      <ul class="navbar-nav ms-auto align-items-lg-center gap-3">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link position-relative" href="cart.php"><i class="bi bi-cart"></i></a></li>

        <?php if ($visitorLoggedIn): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person"></i></a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><span class="dropdown-item-text">Hello, <?= htmlspecialchars($_SESSION['visitor']['name']); ?></span></li>
              <li><a class="dropdown-item" href="my_account.php">My Account</a></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="visitor_login.php"><i class="bi bi-person"></i></a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
