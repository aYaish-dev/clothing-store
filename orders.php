<?php
require_once 'session.php';
include 'db.php';

// فقط للمشرفين
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM orders ORDER BY order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">📦 All Orders</h2>

  <table class="table table-bordered table-striped text-center">
    <thead class="table-dark">
      <tr>
        <th>Order #</th>
        <th>User ID</th>
        <th>Username</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['user_id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['address']) ?></td>
          <td><?= $row['phone'] ?></td>
          <td>$<?= number_format($row['total'], 2) ?></td>
          <td>
            <form method="post" action="update_order_status.php" class="d-flex justify-content-center">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <select name="status" class="form-select form-select-sm w-auto">
                <?php $current = isset($row['status']) ? $row['status'] : 'pending'; ?>
                <?php foreach(['pending','preparing','completed'] as $st): ?>
                  <option value="<?= $st ?>" <?= $st === $current ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-sm btn-primary ms-2">Update</button>
            </form>
          </td>
          <td><?= $row['order_date'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
