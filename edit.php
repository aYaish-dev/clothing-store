<?php
require_once 'session.php';
include 'db.php';

// Admin Protection
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product data
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $name = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $description = trim($_POST['description']);
        $category_id = (int)$_POST['category_id'];
    $stock_xs = (int)($_POST['stock_xs'] ?? 0);
    $stock_s = (int)($_POST['stock_s'] ?? 0);
    $stock_m = (int)($_POST['stock_m'] ?? 0);
    $stock_l = (int)($_POST['stock_l'] ?? 0);
    $stock_xl = (int)($_POST['stock_xl'] ?? 0);
    $stock_xxl = (int)($_POST['stock_xxl'] ?? 0);

    // Keep current image by default
    $imageName = $product['image'];

    // Handle optional new image upload
    if (!empty($_FILES['image']['name'])) {
        $allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize      = 2 * 1024 * 1024; // 2MB

        $imageFile = $_FILES['image'];
        $ext  = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $mime = mime_content_type($imageFile['tmp_name']);

        if ($imageFile['size'] > $maxSize) {
            $error = "❌ Image exceeds size limit.";
        } elseif (!in_array($ext, $allowedExts) || !in_array($mime, $allowedTypes)) {
            $error = "❌ Invalid image type.";
        } else {
            $newName = uniqid('img_', true) . '.' . $ext;
            if (move_uploaded_file($imageFile['tmp_name'], 'uploads/' . $newName)) {
                $imageName = $newName;
            } else {
                $error = "❌ Image upload failed.";
            }
        }
    }

    if (!$error) {
        $update = mysqli_prepare($conn, "UPDATE products SET name=?, price=?, description=?, category_id=?, image=?, stock_xs=?, stock_s=?, stock_m=?, stock_l=?, stock_xl=?, stock_xxl=? WHERE id=?");
        mysqli_stmt_bind_param($update, "sdsdsiiiiiii", $name, $price, $description, $category_id, $imageName, $stock_xs, $stock_s, $stock_m, $stock_l, $stock_xl, $stock_xxl, $id);
        if (mysqli_stmt_execute($update)) {
            $sizes = ['XS' => $stock_xs, 'S' => $stock_s, 'M' => $stock_m, 'L' => $stock_l, 'XL' => $stock_xl, 'XXL' => $stock_xxl];
            foreach ($sizes as $sz => $qty) {
                $size_stmt = mysqli_prepare($conn, "UPDATE product_sizes SET quantity=? WHERE product_id=? AND size=?");
                mysqli_stmt_bind_param($size_stmt, "iis", $qty, $id, $sz);
                mysqli_stmt_execute($size_stmt);
            }

            $success = "✅ Product updated successfully.";
            // Refresh product info for the form
            $product['name'] = $name;
            $product['price'] = $price;
            $product['description'] = $description;
            $product['category_id'] = $category_id;
            $product['image'] = $imageName;
            $product['stock_xs'] = $stock_xs;
            $product['stock_s'] = $stock_s;
            $product['stock_m'] = $stock_m;
            $product['stock_l'] = $stock_l;
            $product['stock_xl'] = $stock_xl;
            $product['stock_xxl'] = $stock_xxl;
        } else {
            $error = "❌ Error updating product.";
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
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price ($):</label>
                <input type="number" name="price" step="0.01" class="form-control" value="<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category:</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php if ($product['category_id'] == $cat['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>
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
                <img src="uploads/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" width="100" class="img-thumbnail mb-2"><br>
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
