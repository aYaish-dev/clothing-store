<?php
session_start();
include 'db.php';

// Admin Protection
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// Get product data
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];
    $stock_xs = $_POST['stock_xs'];
    $stock_s = $_POST['stock_s'];
    $stock_m = $_POST['stock_m'];
    $stock_l = $_POST['stock_l'];
    $stock_xl = $_POST['stock_xl'];
    $stock_xxl = $_POST['stock_xxl'];


        if (mysqli_query($conn, $update)) {
            $success = "✅ Product updated successfully!";
            $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));
        } else {
            $error = "❌ Error updating product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4">✏️ Edit Product</h2>

    <?php if ($success): ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow mx-auto" style="max-width: 600px;">
        <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price ($):</label>
                <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $product['price']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" rows="3" required><?php echo $product['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category:</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($product['category_id'] == $cat['id']) echo 'selected'; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <h5 class="mb-2 fw-bold">Stock per Size:</h5>
            <div class="row">
              <div class="col-6 mb-2">
                <label class="form-label">XS:</label>
                <input type="number" name="stock_xs" class="form-control" value="<?= $product['stock_xs']; ?>" min="0">
              </div>
              <div class="col-6 mb-2">
                <label class="form-label">S:</label>
                <input type="number" name="stock_s" class="form-control" value="<?= $product['stock_s']; ?>" min="0">
              </div>
              <div class="col-6 mb-2">
                <label class="form-label">M:</label>
                <input type="number" name="stock_m" class="form-control" value="<?= $product['stock_m']; ?>" min="0">
              </div>
              <div class="col-6 mb-2">
                <label class="form-label">L:</label>
                <input type="number" name="stock_l" class="form-control" value="<?= $product['stock_l']; ?>" min="0">
              </div>
              <div class="col-6 mb-2">
                <label class="form-label">XL:</label>
                <input type="number" name="stock_xl" class="form-control" value="<?= $product['stock_xl']; ?>" min="0">
              </div>
              <div class="col-6 mb-3">
                <label class="form-label">XXL:</label>
                <input type="number" name="stock_xxl" class="form-control" value="<?= $product['stock_xxl']; ?>" min="0">
              </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image:</label><br>
                <img src="uploads/<?php echo $product['image']; ?>" width="100" class="img-thumbnail mb-2"><br>
                <label class="form-label">Change Image (optional):</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="text-center mb-2">
                <button type="submit" class="btn btn-warning w-100">Update Product ✅</button>
            </div>

            <div class="text-center">
                <a href="admin.php" class="btn btn-secondary w-100">← Back to Dashboard</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
