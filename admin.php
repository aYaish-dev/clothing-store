<?php
session_start();
include 'db.php';

// حماية الأدمن
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// جلب المنتجات
$query = "SELECT products.*, categories.name AS category_name 
          FROM products 
          LEFT JOIN categories ON products.category_id = categories.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">
<?php include 'navbar_admin.php'; ?>
<div class="container py-5">

    

    

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><img src="uploads/<?php echo $row['image']; ?>" width="80" class="img-thumbnail"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td><span class="badge bg-secondary"><?php echo $row['category_name']; ?></span></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
